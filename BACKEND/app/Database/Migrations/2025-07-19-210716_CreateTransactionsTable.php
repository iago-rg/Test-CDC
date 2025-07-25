<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTransactionsTable extends Migration
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
            'id_user_ins' => [
                'type' => 'int',
                'null' => true,
                'comment' => 'Número de identificação do usuário que inseriu o registro.',
            ],
            'id_user_del' => [
                'type' => 'int',
                'null' => true,
                'comment' => 'Número de identificação do usuário que deletou o registro.',
            ],
            'sender_id' => [
                'type' => 'INT',
                'null' => false,
                'comment' => 'Número de identificação do usuário que enviou a transação.',
            ],
            'receiver_id' => [
                'type' => 'INT',
                'null' => false,
                'comment' => 'Número de identificação do usuário que recebeu a transação.',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'comment' => 'Valor da transação.',
            ],
            'date_transaction' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'comment' => 'Data e hora de inserção da transação.',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sender_id', 'customers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'customers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
