<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%e_attendance}}`.
 */
class m200516_025509_create_e_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        $description = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $description = 'Davomat jadvali';
		
        $this->createTable('e_attendance', [
            'id' => $this->primaryKey(),
            '_subject_schedule'=>$this->integer()->notNull(),
            '_student'=>$this->integer()->notNull(),
			'_education_year'=>$this->string(64)->notNull(),
			'_semester'=>$this->string(64)->notNull(),
			'_subject'=>$this->integer()->notNull(),
			'_training_type'=>$this->string(64)->notNull(),
			'_lesson_pair'=>$this->string(64)->notNull(),
			'lesson_date'=>$this->date()->notNull(),
            '_employee'=>$this->integer()->notNull(),
			'absent_on' => $this->smallInteger(),
			'absent_off' => $this->smallInteger(),
			'active' => $this->boolean()->defaultValue(true),
			'updated_at' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
		
        $this->addForeignKey(
            'fk_e_subject_schedule_e_attendance_fkey',
            'e_attendance',
            '_subject_schedule',
            'e_subject_schedule',
            'id',
            'CASCADE'
        );
		
        $this->addForeignKey(
            'fk_e_student_e_attendance_fkey',
            'e_attendance',
            '_student',
            'e_student',
            'id',
            'CASCADE'
        );
		
        $this->addForeignKey(
            'fk_h_education_year_e_attendance_fkey',
            'e_attendance',
            '_education_year',
            'h_education_year',
            'code',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk_e_subject_e_attendance_fkey',
            'e_attendance',
            '_subject',
            'e_subject',
            'id',
            'CASCADE'
        );
		
		$this->addForeignKey(
            'fk_training_type_e_attendance_fkey',
            'e_attendance',
            '_training_type',
            'h_training_type',
            'code',
            'CASCADE'
        );
		
        $this->addForeignKey(
            'fk_e_employee_e_attendance_fkey',
            'e_attendance',
            '_employee',
            'e_employee',
            'id',
            'CASCADE'
        );
		
        $this->createIndex('e_attendance_student_uniq',
            'e_attendance',
            ['_student','_semester','_subject','_training_type','_lesson_pair','lesson_date'],
            true);
			
        $this->addCommentOnTable('e_attendance', $description);
    }

    public function safeDown()
    {
        $this->dropTable('e_attendance');
    }
}
