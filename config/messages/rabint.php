<?php

return \yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/_base.php'),
    [
        'languages' => ['fa', 'en'],
        // 'php' output format is for saving messages to php files.
        'format' => 'php',
        // Root directory containing message translations.
        'messagePath' => Yii::getAlias('@rabint/messages'),
        // boolean, whether the message file should be overwritten with the merged messages
        'overwrite' => true,
        'only' => ['/vendor/rabint/*.php'],
        'except' => [
            '.svn',
            '.git',
            '.gitignore',
            '.gitkeep',
            '.hgignore',
            '.hgkeep',
            '/messages',
            '/vendor',
        ],
    ]
);
