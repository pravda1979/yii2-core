<?php

/* @var $this yii\web\View */
/* @var $changes \pravda1979\core\models\BackupAttribute[] */

?>
<div class="backup-changes">
    <?php foreach ($changes as $attribute) { ?>
        <div class="attribute">
            <span class="attribute-name"><?= $attribute->attributeName ?></span>
            <span class="attribute-value"><?= Yii::$app->formatter->asHtml($attribute->getChanges()) ?></span>
        </div>
    <?php } ?>
</div>