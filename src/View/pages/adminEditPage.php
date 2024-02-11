<?php

use N_ONE\App\Model\Entity;
use N_ONE\App\Model\Item;

/**
 * @var Entity $item
 */
$fields = array_flip($item->getFieldNames(true));
// var_dump($item->getField('tags')[0]->getTitle());
?>
<div class="edit-form-container">
	<form action="" class="edit-form" method="post">
		<div class="form-section">
			<p>ID сущности: <?= $item->getId() ?></p>
			<?php foreach ($fields as $field => $value): ?>
				<?php if ($field === 'tags' || $field === 'images' || $field === 'id' || $field === 'dateCreate'): {
					continue;
				} endif ?>
				<label for="<?= $field ?>">
					<?= $field ?>:
					<input type="text" name="<?= $field ?>" value="<?= $item->getField($field) ?>">
				</label>
			<?php endforeach; ?>
		</div>
		<div class="form-section">
			<?php if ($item instanceof Item): ?>
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
			<?php endif; ?>
			<button class="submit-button" type="submit">Сохранить</button>

		</div>
	</form>
</div>