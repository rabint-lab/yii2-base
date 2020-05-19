generate migrations:
---
php yii migrate-generate/create-all


generate translations (extract messages from code):
---
rabint:
$ php yii message/extract @rabint/config/messages/rabint.php

applications:
$ php yii message/extract config/message.php

rbac-migrate
---
$ php yii rbac-migrate
$ php yii rbac-migrate/down


migrate
---
$ php yii migrate --migrationPath=../vendor/sahifedp/impost/migrations
$ php yii migrate --migrationPath=@vendor/rkit/filemanager-yii2/src/migrations/ --interactive=0
    
GII 
---
$ php yii gii/crud --modelClass=app\modules\post\models\Post --searchModelClass=app\modules\post\models\search\PostSearch --controllerClass=app\modules\post\controllers\AdminController --viewPath=@app/modules/post/views/admin --enableI18N=1 --enablePjax=1  --indexWidgetType=grid --messageCategory=sdp --overwrite=1 --template=sdpBackendCrud


