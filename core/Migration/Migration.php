<?php

namespace Migration;

class Migration
{
	public static function migrate()
	{
		// 1. смотрим последнюю применённую миграцию, которая записана в таблице migration (если таблица пуста то делаем все миграции)
		// 2. проходимся по /core/Migration/migrations и ищем новые миграции
		// 3. выполняем новые миграции
		// 4. обновляем данные о последней применённой миграции в таблице migration
	}

}