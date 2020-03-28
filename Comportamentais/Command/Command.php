<?php
interface Command
{
    public static function execute(...$params): bool;
}

class SellsReport implements Command
{
    public static function execute(...$params): bool
    {
        // Um monte de querys para buscar as vendas do vendedor no periodo
        return (new self)->sendReport();
    }

    private function sendReport(): bool
    {
        // Forma aleatoria de dizer se uma queue foi ou não executada
        $sended = rand(0, 1) == 1;
        echo ($sended) ? "\tRelatório enviado!\n" : "\tFalha no envio\n";
        return $sended;
    }
}

class Queue
{
    private array $commands;

    public function __construct()
    {
        $this->commands = $this->list();
    }

    public function start()
    {
        if (!$this->commands) {
            echo "Sem Queues";
        }

        while (!empty($this->commands)) {
            $command = $this->commands[rand(0, count($this->commands) - 1)];
            echo sprintf("QueueID: %d - executando\n", $command->id);

            $function = sprintf("%s::execute", $command->className);
            $params   = (isset($command->params)) ? (array) $command->params : [];
            $executed = call_user_func_array($function, $params);

            if ($executed) {
                $this->markDone($command->id);
            }
        }
    }

    /**
     * Busca as instruções da Queue
     *
     * @return array
     */
    private function list(): array
    {
        // Função para buscar em um banco de dados os elementos de uma queue
        // como o foco não é isso, vou retornar uma senha padrão
        $queueStr = '[ { "id": 474, "className": "SellsReport", "params": [] }, { "id": 475, "className": "SellsReport", "params": [] }, { "id": 477, "className": "SellsReport", "params": [] } ]';
        return json_decode($queueStr);
    }

    /**
     * Marca uma instrução como concluída
     *
     * @param int $id
     * @return void
     */
    private function markDone(int $id)
    {
        echo sprintf("QueueID: %d - concluída\n", $id);
        $this->commands = array_values(array_filter($this->commands, function ($command) use ($id) {
            return $command->id !== $id;
        }));
    }
}

(new Queue)->start();
