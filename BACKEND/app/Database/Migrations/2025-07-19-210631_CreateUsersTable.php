<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'comment' => 'Número sequencial único do registro.',
            ],
            'uuid' => [
                'type' => 'VARCHAR',
                'constraint' => 42,
                'default' => new RawSql('(uuid())'),
                'comment' => 'UUID único para o registro.',
            ],
            'date_ins' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'comment' => 'Data e hora de inserção do registro.',
            ],
            'date_upd TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT "Data e hora da última alteração do registro."',
            'date_del' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
                'comment' => 'Data e hora de exclusão do registro. (Os registros com este campo preenchido, não serão mostrados no sistema).',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Email do usuário',
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Senha do usuário',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
