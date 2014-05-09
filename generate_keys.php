<?php


$privkey = openssl_pkey_new(array(
    'private_key_bits' => 1024,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
));

openssl_pkey_export_to_file($privkey, 'private.key');

$pubkey = openssl_pkey_get_details($privkey);

file_put_contents('public.key', $pubkey['key']);

openssl_free_key($privkey);

?>