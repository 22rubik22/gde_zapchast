import pandas as pd
from sqlalchemy import create_engine, MetaData, update, text

username = "root"
password = ""
host = "127.0.0.1"
port = "3306"
database = "21037_where_parts_db"
table_name = "adverts"

engine = create_engine(f'mysql+pymysql://{username}:{password}@{host}:{port}/{database}')

def add_dataframe_to_db(data: pd.DataFrame, user_id:int, deactivate_old_ad: bool):

    if deactivate_old_ad:
        metadata = MetaData()
        metadata.reflect(bind=engine)
        table = metadata.tables[table_name]
        with engine.connect() as connection:
            stmt = (
                update(table)
                .where(table.c.user_id == user_id)
                .values(status_ad='noactiv')
            )
            connection.execute(stmt)
            connection.commit()

    # Добавляем данные в таблицу
    # index=False предотвращает добавление индекса DataFrame в таблицу базы данных
    data.to_sql(table_name, con=engine, if_exists='append', index=False)

def get_users_in_queue():
    with engine.connect() as connection:
        result = connection.execute(text("SELECT value FROM single_value WHERE id = 1"))
        row = result.fetchone()
        return row.value if row else None

def set_users_in_queue(value):
    with engine.connect() as connection:
        connection.execute(text("UPDATE single_value SET value = :value WHERE id = 1"), {'value': value})
        connection.commit()

def increment_users_in_queue():
    with engine.connect() as connection:
        connection.execute(text("UPDATE single_value SET value = value + 1 WHERE id = 1"))
        connection.commit()

def decrement_users_in_queue():
    with engine.connect() as connection:
        current_value = get_users_in_queue()
        if current_value is not None and current_value > 0:
            connection.execute(text("UPDATE single_value SET value = value - 1 WHERE id = 1"))
            connection.commit()

'''
CREATE TABLE single_value (
    id INT PRIMARY KEY,
    value INT NOT NULL
);

-- Вставить начальное значение
INSERT INTO single_value (id, value) VALUES (1, 0);
'''
