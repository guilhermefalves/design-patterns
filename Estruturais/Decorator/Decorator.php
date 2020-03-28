<?php

    /**
     * Interface para os decorators
     */
    interface Coin
    {
        public function format(float $value): string;
    }

    /**
     * Decorator para a moeda estadunidense
     */
    class Dollar implements Coin
    {
        public function format(float $value): string
        {
            return sprintf("US$ %s", number_format($value, 2));
        }
    }

    /**
     * Decorator para a moeda brasileira
     */
    class Real implements Coin
    {
        public function format(float $value): string
        {
            return sprintf("R$ %s", number_format($value, 2, ',', '.'));
        }
    }

    /**
     * Classe base para qualquer item
     */
    class Item
    {
        public string $name;
        public float  $price;

        public function __construct(string $name, float $price)
        {
            $this->name  = $name;
            $this->price = $price;
        }

        /**
         * Retorna o price formatado
         *
         * @param Coin $coin decorator do item
         * @return string
         */
        private function priceToCoin(Coin $coin): string
        {
            return $coin->format($this->price);
        }

        /**
         * Retorna uma string com os dados do item
         *
         * @param Coin $coin - decorator do item
         * @return string
         */
        public function toString(Coin $coin = null): string
        {
            // Se não for passado nenhum tipo de moeda, retorno o valor sem tratamento
            if ($coin == null) {
                return sprintf("%s %s\n", $this->name, $this->price);
            }

            return sprintf("%s %s\n", $this->name, $this->priceToCoin($coin));
        }
    }

    $item = new Item("Computador", 3829.99);
    echo sprintf("Item em cotação brasileira: %s" , $item->toString(new Real));
    echo sprintf("Item em cotação estadunidense: %s" , $item->toString(new Dollar));