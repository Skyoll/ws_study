<?php

require_once 'vendor/autoload.php';

use Classes\ClientSocketDataBuilder;
use Classes\SocketException;

$settings = parse_ini_file('socket.ini');

$client = (new ClientSocketDataBuilder())
    ->setDomainServerSocketFilePath($settings['SERVER_SOCK_FILE_PATH'])
    ->setProtocolFamilyForSocket(AF_UNIX)
    ->setTypeOfDataExchange(SOCK_STREAM)
    ->setProtocol(0)
    ->setMaxByteForRead(65536)
    ->built();

try {
    $socket = $client->socketCreate();
    echo "Сокет создан\n";

    $res = $client->connect($socket);
    echo "Сокет успешно связан с адресом и портом\n";
} catch(Throwable $e){
    echo $e->getMessage();
}

do {
    try {
        $out = $client->read($socket);
    } catch (SocketException $e) {
        echo $e->getMessage();
    }
    echo "Сообщение от сервера: $out.\n";
    $msg = 'Принято';
    $client->write($socket, $msg);
    sleep(2);
} while(true);


if (isset($socket)) {
    $client->socketClose($socket);
    echo 'Сокет успешно закрыт';
}
