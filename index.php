<?php
    $coinsLeft=[ //wending machine coins left
        '100'=>4,
        '50'=>3,
        '20'=>4,
        '10'=>10,
        '5'=>40,
        '2'=>50,
        '1'=>60
    ];
    $userCoins=[ //user coins left
        '100'=>10,
        '50'=>0,
        '20'=>0,
        '10'=>10,
        '5'=>5,
        '2'=>1,
        '1'=>0
    ];
    function createProduct(string $name, int $value) :stdClass{
        $product = new stdClass();
        $product->name=$name;
        $product->value=$value;
        return($product);
    }
    $products[]=createProduct('Waffles', 1001);
    $products[]=createProduct('Snickers', 576);
    $products[]=createProduct('Coca cola', 428);
    $products[]=createProduct('Sprite', 325);
    foreach($products as $key=>$product){
        echo "$key: $product->name: $product->value \n";
    }
    $balance=10000;

    while(true) {
        $balance=0;
        foreach ($userCoins as $key=>$coins){
            $balance+=$key*$coins;
        }
        echo 'User has ' . $balance . '$' . PHP_EOL;
        $userChoice = (int)readline('Enter product key or 100 for display coins: ');
        if(!array_key_exists($userChoice,$products)){
            if($userChoice===100){
                echo 'User coins: ' . PHP_EOL;
                foreach ($userCoins as $key=>$coins){
                    echo "$key: $coins\n";
                }
                echo 'Wending machine coins: ' . PHP_EOL;
                foreach ($coinsLeft as $key=>$coins){
                    echo "$key: $coins\n";
                }
                continue;
            }
            echo 'not a valid product' . PHP_EOL;
            exit;
        }
        echo PHP_EOL;
        $need = $products[$userChoice]->value;
        //user coins input in wending machine
        $input = [];
        foreach (array_reverse($userCoins, true) as $key => $count) {
            $counter = 0;
            while ($count > 0) {
                $count--;
                $counter++;
                $need -= intval($key);
                if ($need <= 0) {
                    break;
                }
            }
            if ($counter > 0) {
                $userCoins[$key] -= $counter;
                $input[$key] = $counter;
            }
            if ($need <= 0) {
                break;
            }
        }
        if ($need <= 0) {
            foreach (array_reverse($input, true) as $key => $coinCount) {
                while ($input[$key] > 0) {
                    if ($need + intval($key) <= 0) {
                        $input[$key]--;
                        $userCoins[$key]++;
                        $need += $key;
                    } else break;
                }

            }
            echo 'User inserted coins' . PHP_EOL;
            foreach ($input as $key => $coinCount) {
                if (array_key_exists($key, $coinsLeft)) {
                    $coinsLeft[$key] += $coinCount;
                } else $coinsLeft[$key] = $coinCount;
                echo "$key: $coinCount \n";
            }
        } else {
            echo 'Go find a job' . PHP_EOL;
            exit;
        }
        echo PHP_EOL;
        //return from wending machine
        $need *= -1;
        $output = [];
        foreach ($coinsLeft as $key => $count) {
            $counter = 0;
            while ($count > 0 && $need >= intval($key)) {
                $count--;
                $counter++;
                $need -= intval($key);
                if ($need <= 0) {
                    break;
                }
            }
            if ($counter > 0) {
                $coinsLeft[$key] -= $counter;
                $output[$key] = $counter;
            }
            if ($need === 0) {
                break;
            }
        }
        if ($need === 0) {
            if (count($output) > 0) {
                echo 'Wending machine output coins' . PHP_EOL;
                foreach ($output as $key => $coinCount) {
                    if (array_key_exists($key, $userCoins)) {
                        $userCoins[$key] += $coinCount;
                    } else $userCoins[$key] = $coinCount;
                    echo "$key: $coinCount \n";
                }
            } else echo 'Nothing to return' . PHP_EOL;
        } else echo('cannot give return from wending machine' . PHP_EOL);
    }







