FROM python:3.11-slim
WORKDIR /app
COPY . .

# RUN python -m venv venv
RUN pip install -r requirements.txt

#CMD pwd; ls .;
CMD python main.py
