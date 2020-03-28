<?php
class Log
{
    /**
     * Armazena uma instância estática da própria classe
     * @var Log
     */
    private static $instance = null;

    // Faço com que todos os métodos que podem instanciar esta classe, sejam privados
    private function __construct() {}
    private function __clone() {}

    /**
     * Retorna uma instância da clas
     * Esse método deveria ser private, porém para exemplificar é public
     * @return Singleton
     */
    public static function getInstance()
    {
        echo "Estamos retornando uma instância da classe\n";
        // Se a classe ainda não tiver sido instanciada, a instancio
        if (self::$instance === null) {
            echo "Porém só passamos aqui e instanciamos a classe uma vez!\n";
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Metodo para realmente salvar o log em algum lugar (Logstash por exemplo)
     * @param string $message
     */
    private function save($message) { /** implementation */ }

    /**
     * Grava um logs
     * @param string $message
     */
    public function log($message)
    {
        // Obtemos uma instância da classe e a utilizamos para salvar o log
        ($this->getInstance())->save($message);
    }
}

$log1 = Log::getInstance();
$log1->log("Um log qualquer");

$log2 = Log::getInstance();
$log2->log("Outro log");

echo sprintf(
    "Ao compararmos duas instancias obtidas, temos %s",
    ($log1 === $log2) ? "igualdade (de tipo e valor)" : "diferenças (de tipo ou valor)"
);
