<?php

// add unit testing specific bootstrap code here

// Алиас @app нужен автозагрузчику Yii для классов app\* в тестах, которые не создают приложение.
Yii::setAlias('@app', dirname(dirname(dirname(__DIR__))));
