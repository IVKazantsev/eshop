<?php

use N_ONE\App\Model\Item;

/**
 * @var Item $item
 */
// var_dump($item);
?>
<div class="edit-form-container">
	<form action="" class="edit-form" method="post">
		<div class="form-section">

			<p>ID товара: <?= $item->getId() ?></p>
			<label for="title">
				Название товара:
				<input type="text" name="title" value="<?= $item->getTitle() ?>">
			</label>
			<label for="price">
				Стоимость:
				<input type="text" name="price" value="<?= $item->getPrice() ?>">
			</label>
			<label for="description">
				Описание:
				<textarea class="description-textarea" name="description"><?= $item->getDescription() ?></textarea>
			</label>
		</div>
		<div class="form-section">
			<p>Теги:</p>
			<div class="tag-group">
				<p>Тип привода:</p>
				<label for="drive-type">
					<input type="radio" name="drive-type" value="Передний">
					Передний
				</label>
				<label for="drive-type">
					<input type="radio" name="drive-type" value="Задний">
					Задний
				</label>
				<label for="drive-type">
					<input type="radio" name="drive-type" value="Полный">
					Полный
				</label>
			</div>
			<div class="tag-group">
				<p>Коробка передач:</p>
				<label for="transmission-type">
					<input type="radio" name="transmission-type" value="МКПП">
					МКПП
				</label>
				<label for="transmission-type">
					<input type="radio" name="transmission-type" value="АКПП">
					АКПП
				</label>
			</div>
			<div class="tag-group">
				<p>Тип топлива:</p>
				<label for="fuel-type">
					<input type="radio" name="fuel-type" value="Бензин">
					Бензин
				</label>
				<label for="fuel-type">
					<input type="radio" name="fuel-type" value="Дизель">
					Дизель
				</label>
			</div>

			<div class="tag-group">
				<p>Тип двигателя:</p>
				<label for="engine-type">
					<input type="radio" name="engine-type" value="Турбированный">
					Турбированный
				</label>
				<label for="engine-type">
					<input type="radio" name="engine-type" value="Атмосферный">
					Атмосферный
				</label>
			</div>
			<button type="submit">Сохранить</button>
		</div>
	</form>
</div>