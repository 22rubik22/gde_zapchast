import logging
from io import BytesIO
from json import loads

import uvicorn
from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.responses import RedirectResponse
from fastapi.middleware.cors import CORSMiddleware

from parser import parse_file_to_df
from db_functions import add_dataframe_to_db, increment_users_in_queue, decrement_users_in_queue, get_users_in_queue, \
    set_users_in_queue

logging.basicConfig(level=logging.INFO)


DEBUG = True
TEMP_FOLDER = "temp"

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Укажите конкретные домены вместо "*", если нужно
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
# Изначальное значение очереди
set_users_in_queue(0)

def log(msg):
    logging.info(msg)

@app.get("/")
async def root():
    return RedirectResponse("/docs")

@app.post("/get_users_in_queue")
async def get_queue():
    return {"value": get_users_in_queue()}

@app.post("/upload")
async def upload(user_id: int, columns: str, file: UploadFile = File(...), skip_rows: int = 0, encoding: str = "auto", delimiter: str = ",", add_sheet_name_to_product_name: bool = True, extract_data_from_product_name: bool = True, skip_empty_price_rows=True, deactivate_old_ad=True, split_symbols=" "):
    # log(f"hi in upload {file.filename=}")
    try:
        columns = loads(columns)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Invalid JSON for param 'columns': {str(e)}")

    # log(f"{columns=}")
    file_stream = BytesIO(await file.read())
    try:
        # log("start parse")
        # todo check if queue is full
        increment_users_in_queue()
        df = parse_file_to_df(file_stream, file.filename, user_id, columns, skip_rows, encoding, delimiter, add_sheet_name_to_product_name, extract_data_from_product_name, skip_empty_price_rows, split_symbols)
        # log("end parse")
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Error while parsing the file:{type(e).__name__} {str(e)}")
    finally:
        decrement_users_in_queue()

    try:
        add_dataframe_to_db(df, user_id, deactivate_old_ad)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Error while inserting to the db:{type(e).__name__} {str(e)}")
    # df.to_excel("result.xlsx")
    # log(f"{df.head(5)}")
    return {
        "message": "File uploaded successfully",
        "filename": file.filename,
        "rows_added": len(df)
    }

if __name__ == "__main__":
    uvicorn.run("main:app", port=8002, host="0.0.0.0", workers=8, log_level="debug" if DEBUG else "")

