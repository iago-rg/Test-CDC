<?php

$arrayCodes = [
    'EM0001' => ['Campos obrigatórios não enviados.', 400],
    'EM0002' => ['Nenhum usuário encontrado para os dados informados.', 400],
    'EM0003' => ['Erro ao cadastrar usuário.', 400],
    'EM0004' => ['Erro ao cadastrar cliente.', 400],
    'EM0005' => ['CPF/CNPJ já existente na base de dados.', 400],
    'EM0006' => ['Erro ao atualizar cliente.', 400],
    'EM0007' => ['Erro ao deletar cliente.', 400],
    'EM0008' => ['Cliente não possui saldo suficiente para realizar a transação.', 400],
    'EM0009' => ['Erro ao cadastrar transação.', 400],
    'EM0010' => ['A data de nascimento/fundação deve estar entre 1900-01-01 e a data de hoje.', 400],
    'EM0011' => ['O CPF/CNPJ do remetente e o CPF/CNPJ do destinatário devem ser diferentes.', 400],

    'JWTE0001' => ['JWT inválido ou não encontrado.', 401],
];
