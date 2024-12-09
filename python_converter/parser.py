import pandas as pd
import chardet
import re
from car_specs import brand_to_models, model_to_body_to_year, brand_to_engines, car_brands


def parse_file_to_df(file, filename, user_id: int, column_names: dict, skip_rows, encoding, delimiter,
                     add_sheet_name_to_product_name, extract_data_from_product_name, skip_empty_price_rows, split_symbols):

    data = 0
    is_excel = False
    extension = filename.rsplit(".")[-1].lower()

    if extension == 'csv':
        if encoding == "auto":
            if file is str:
                with open(file, 'rb') as f:
                    result = chardet.detect(f.read())
            else:
                result = chardet.detect(file.read())
                file.seek(0)
            encoding = result['encoding']
        data = pd.read_csv(file, encoding=encoding, delimiter=delimiter, skiprows=skip_rows)

    elif extension == 'json':
        data = pd.read_json(file)

    elif extension in ['xls', 'xlsx']:
        # Read all sheets in Excel file into a dictionary of DataFrames
        is_excel = True
        excel_file = pd.ExcelFile(file)
        sheet_names = excel_file.sheet_names

        data = dict()
        for sheet in sheet_names:
            df = excel_file.parse(sheet, skiprows=skip_rows)
            for sheet1 in data:
                if list(data[sheet1].columns) != list(df.columns):
                    raise ValueError("Only identical columns in sheets are supported")
            data[sheet] = df

    else:
        raise ValueError("Unsupported file extension.")

    result_df = pd.DataFrame()

    if is_excel:
        for sheet in data:
            add_name = ""
            if add_sheet_name_to_product_name:
                add_name = " "+sheet
            df = format_df_for_db(data[sheet], user_id, column_names, add_name, extract_data_from_product_name, skip_empty_price_rows, split_symbols)
            result_df = pd.concat([result_df, df], ignore_index=True)

    else:
        # if not excel
        result_df = format_df_for_db(data, user_id, column_names, "", extract_data_from_product_name, skip_empty_price_rows, split_symbols)

    return result_df


def format_df_for_db(data: pd.DataFrame, user_id: int, column_names: dict, append_to_product_name: str, extract_data_from_product_name: bool, skip_empty_price_rows: bool, split_symbols:str):
    right_columns = ['user_id', 'art_number', 'product_name', 'new_used', 'brand', 'model', 'body', 'number', 'engine',
               'year', 'L_R', 'F_R', 'U_D', 'color', 'applicability', 'quantity', 'price', 'availability',
               'delivery_time', 'data', 'status_ad', 'id_ad', 'created_at', 'updated_at', 'new_column',
               'another_column', 'main_photo_url', 'additional_photo_url_1', 'additional_photo_url_2',
               'additional_photo_url_3']
    string_columns = ['art_number', 'product_name', 'new_used', 'brand', 'model', 'body', 'number', 'engine',
                     'L_R', 'F_R', 'U_D', 'color', 'applicability', 'quantity', 'availability',
                     'delivery_time', 'id_ad', 'new_column',
                     'main_photo_url', 'additional_photo_url_1', 'additional_photo_url_2',
                     'additional_photo_url_3']

    result_df = pd.DataFrame(columns=right_columns)

    for col in right_columns:
        if col in column_names.keys() and column_names[col] in data.columns:
            result_df[col] = data[column_names[col]]
        else:
            result_df[col] = pd.NA  # Для отсутствующих столбцов ставим NaN (или pd.NA)

    result_df['user_id'] = int(user_id)
    result_df['status_ad'] = 'activ'

    # Приведение столбцов в нужный формат

    if append_to_product_name:
        result_df['product_name'] = result_df['product_name'] + append_to_product_name

    # Преобразование всех строковых значений в нижний регистр и удаление NaN
    for col in string_columns:
        result_df[col] = result_df[col].fillna('').astype(str).str.lower()

    price = result_df['price'].fillna("").astype(str).str.replace(",", ".").str.replace(r"[^\d.]", "", regex=True)
    result_df['price'] = pd.to_numeric(price, errors='coerce').astype(float)
    if skip_empty_price_rows: # удаление NaN и нулевых цен
        result_df = result_df[result_df['price']>0]

    result_df['data'] = pd.to_datetime(result_df['data'], errors='coerce')
    result_df['created_at'] = pd.to_datetime(result_df['created_at'], errors='coerce')
    result_df['updated_at'] = pd.to_datetime(result_df['updated_at'], errors='coerce')

    result_df['another_column'] = pd.to_numeric(result_df['another_column'], errors='coerce').fillna(0).astype(int)

    result_df['year'] = result_df['year'].fillna("").apply(lambda x: str(int(x)) if x != "" else x).str.replace(r"[^\d]", "", regex=True)

    # Удаление всех символов, кроме букв (разных языков) и цифр
    for col in ["art_number", "engine", "number", "new_used"]:
        result_df[col] = result_df[col].str.replace(r'[^a-zA-Zа-яА-ЯёЁ\d\u4e00-\u9fff]', '', regex=True)

    # Заполнение дополнительных недостающих данных из 'product_name'
    if extract_data_from_product_name:
        def fill_missing_data(row):
            pattern = f"[{re.escape(split_symbols)}]"
            key_words = re.split(pattern, row['product_name'])
            car_brand = row['brand']
            car_model = row['model']
            car_engine = row['engine']
            car_body = row['body']
            car_year = row['year']

            if not car_brand:  # Если значение пустое
                for w in key_words:
                    if w in car_brands:
                        car_brand = w
                        row['brand'] = w

                    # Если всё ещё бренд не найден
                    if not car_brand and car_model:
                        for brand in brand_to_models:
                            if car_model in brand_to_models[brand]:
                                car_brand = brand
                                row['brand'] = brand

            if not car_model:
                def check_model_for_brand(brand_arg):
                    nonlocal car_model
                    if brand_arg in brand_to_models:
                        for word in key_words:
                            if word in brand_to_models[brand_arg] and len(car_model)<len(word):
                                car_model = word
                                row['model'] = word
                                row['brand'] = brand_arg

                if car_brand:
                    check_model_for_brand(car_brand)
                else:
                    for brand in car_brands:
                        check_model_for_brand(brand)

            if not car_engine:
                def check_engine_for_brand(brand_arg):
                    nonlocal car_engine
                    if brand_arg in brand_to_engines:
                        for word in key_words:
                            if word in brand_to_engines[brand_arg] and len(car_engine)<len(word):
                                car_engine = word
                                row['engine'] = word
                                row['brand'] = brand_arg

                if car_brand:
                    check_engine_for_brand(car_brand)
                else:
                    for brand in car_brands:
                        check_engine_for_brand(brand)

            if car_model and not car_body:
                if car_model in model_to_body_to_year:
                    for word in key_words:
                        if word in model_to_body_to_year[car_model] and len(car_body)<len(word):
                            car_body = word
                            row['body'] = word

            if car_model and car_body and not car_year:
                bodies = model_to_body_to_year.get(car_model)
                if bodies:
                    year = bodies.get(car_body)
                    if year:
                        row['year'] = str(year)

            return row

        # Применяем функцию
        result_df = result_df.apply(fill_missing_data, axis=1)

    # Удаление дубликатов
    result_df = result_df.drop_duplicates()

    return result_df
