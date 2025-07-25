<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateCustomersTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
                'comment' => 'Nome / Razão social do cliente',
            ],
            'document_id' => [
                'type' => 'VARCHAR',
                'constraint' => 14,
                'null' => true,
                'comment' => 'CPF/CNPJ do cliente',
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Data de nascimento ou fundação do cliente',
            ],
            'monthly_income' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'comment' => 'Renda mensal do cliente',
            ],
            'balance' => [
                'type' => 'DECIMAL',
                'constraint' => '14,2',
                'default' => 0.00,
                'comment' => 'Saldo do cliente',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('customers', true);
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}
