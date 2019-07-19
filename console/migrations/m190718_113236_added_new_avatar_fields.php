<?php

use yii\db\Migration;

/**
 * Class m190718_113236_added_new_avatar_fields
 */
class m190718_113236_added_new_avatar_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%admin_user}}', 'avatar_extension', $this->string(10)->defaultValue(null)->after('avatar'));
        $this->addColumn('{{%admin_user}}', 'avatar_mime_type', $this->string(100)->defaultValue(null)->after('avatar_extension'));
        $this->addColumn('{{%admin_user}}', 'avatar_size', $this->integer(10)->unsigned()->defaultValue(null)->after('avatar_mime_type'));
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%admin_user}}', 'avatar_extension');
        $this->dropColumn('{{%admin_user}}', 'avatar_mime_type');
        $this->dropColumn('{{%admin_user}}', 'avatar_size');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190718_113236_added_new_avatar_fields cannot be reverted.\n";

        return false;
    }
    */
}
