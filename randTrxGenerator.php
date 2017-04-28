<?php

	// RAND TRX GENERATOR V1.0
$contador = 1;

do{
	require_once 'vendor/autoload.php';

    $faker = Faker\Factory::create();
    
    $faker->name;
    $faker->streetName;
    $faker->creditCardNumber;
    $faker->email;

    $n1 = rand(10000,99999);
    $n2 = rand(1000,9999);
    $tel = $n1.$n2;
    $ddd = (string) rand(10,40);

    // Gerando um CPF aleatório
    $num = array();
    $num[9]=$num[10]=$num[11]=0;
    for ($w=0; $w > -2; $w--){
        for($i=$w; $i < 9; $i++){
            $x=($i-10)*-1;
            $w==0?$num[$i]=rand(0,9):'';
            ($w==-1 && $i==$w && $num[11]==0)?
                $num[11]+=$num[10]*2    :
                $num[10-$w]+=$num[$i-$w]*$x;
        }
        $num[10-$w]=(($num[10-$w]%11)<2?0:(11-($num[10-$w]%11)));
    }
    $cpf = $num[0].$num[1].$num[2].$num[3].$num[4].$num[5].$num[6].$num[7].$num[8].$num[10].$num[11];

	require __DIR__.'/vendor/autoload.php';

	$pagarMe = new \PagarMe\Sdk\PagarMe('ak_test_IIcjTAV18c82AahlM7CgPtWgbqDAvn');

	$amount = rand(100,100000);
	$installments = rand(1,12);
	$capture = true;
	$postbackUrl = 'http://requestb.in/pkt7pgpk';

	//TRANSAÇÃO DE CARTÃO DE CRÉDITO
	$customer = new \PagarMe\Sdk\Customer\Customer(
		[
		    'name' => $faker->name,
		    'email' => $faker->email,
		    'document_number' => $cpf,
		    'address' => [
	            'street'        => $faker->streetName,
	            'street_number' => rand(10, 5000),
	            'neighborhood'  => 'centro',
	            'zipcode'       => '01227200',
	            'complementary' => 'Apto 42',
	            'city'          => 'São Paulo',
	            'state'         => 'SP',
	            'country'       => 'Brasil'
	        ], 
		    'phone' => [
	            'ddd'    => $ddd,
	            'number' => $tel
	        ], 
		    'born_at' => '15021994',
		    'sex' => 'M'
	    ] 
	); 
	$card = $pagarMe->card()->create(
	    $faker->creditCardNumber,
	    $faker->name,
	    '1224'
	);

	$transaction = $pagarMe->transaction()->creditCardTransaction(
	    $amount,
	    $card,
	    $customer,
	    $installments,
	    $capture,
	    $postbackUrl
	);
	$contador++;
} while ($contador <= 20);

