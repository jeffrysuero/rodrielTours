
<?php

class ObtieneMails
{

    //usuario de gmail, email a donde deseamos conectarnos
    // var $user = "developjeff04@gmail.com";
    var $user = 'developjeff04@gmail.com';
    //password de nuestro email
    var $password = 'xgle xmaf xvua znkv';
    //inforrmación necesaria para conectarnos al INBOX de gmail,
    //incluye el servidor, el puerto 993 que es para imap, e indicamos que no valide con ssl
    var $mailbox = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";

    //var $fecha = "01-MAR-2015"; //desde que fecha sincronizara

    //metodo que realiza todo el trabajo
    function obtenerAsuntosDelMails()
    {
        $servername = '34.174.63.119';
        $username = 'dbe5qgcomzzgcg';
        $password = 'u2aflvpvotnxz';
        $database = 'administrador';

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $database);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $fecha = date('d-M-Y');

        // echo $fecha;
        $inbox = imap_open($this->mailbox, $this->user, $this->password) or die('Cannot connect to Gmail: ' . imap_last_error());

        $emails = imap_search($inbox, 'UNSEEN SINCE "' . $fecha . '" FROM "Rodriguez Rodriguez"');


        if ($emails) {
            // Ahora recorremos los correos
            foreach ($emails as $email_number) {
                // Leemos el correo completo
                $headerInfo = imap_headerinfo($inbox, $email_number);
                $body = imap_body($inbox, $email_number); // Obtenemos el cuerpo del correo

                $patterns = [
                    'name' => '/Name:\s*\*([^\*]+)\*/',
                    'phone_number' => '/Phone number:\s*\*([^\*]+)\*/',
                    'driver_link' => '/Driver link:\s*\*([^\*]+)\*/',
                    'journey_code' => '/Journey code:\s*\*([^\*]+)\*/',
                    'pickup_date' => '/Pickup date:\s*\*([^\*]+)\*/',
                    'from_location' => '/From:\s*\*([^\*]+)\*/',
                    'to_location' => '/To:\s*\*([^\*]+)\*/',
                    'flight_number' => '/Flight number:\s*\*([^\*]+)\*/',
                    'travellers' => '/Travellers:\s*\*([^\*]+)\*/',
                    'suitcases' => '/Suitcases:\s*\*([^\*]+)\*/',
                    'meet_greet' => '/Meet & greet:\s*\*([^\*]+)\*/',
                    'Add_ons' => '/Add-ons:\s*\*([^\*]+)\*/',
                    'comments' => '/Comments:\s*\*([^\*]+)\*/',
                    'vehicle_category' => '/Vehicle category:\s*\*([^\*]+)\*/',
                    'partner_reference' => '/Partner reference\s*\*([^\*]+)\*/'
                ];

                $columnNames = implode(', ', array_keys($patterns));

                $values = [];
                // Itera sobre cada patrón y busca coincidencias en el cuerpo del correo
                foreach ($patterns as $pattern) {
                    preg_match($pattern, $body, $matches);
                    $values[] = isset($matches[1]) ? "'" . $matches[1] . "'" : "NULL";
                }

                $values = implode(', ', $values);
                // Prepara la consulta SQL para insertar los valores
                $sql = "INSERT INTO transferzs ($columnNames) VALUES ($values)";

                // Ejecuta la consulta
                if ($conn->query($sql) === TRUE) {
                    echo "Registro insertado correctamente.<br>";
                } else {
                    echo "Error al insertar el registro: " . $conn->error . "<br>";
                }

                // Cierra la conexión
                // $conn->close();

            }
        }
    }

    //arregla texto de asunto
    function fix_text_subject($str)
    {
        $subject = '';
        $subject_array = imap_mime_header_decode($str);

        foreach ($subject_array as $obj)
            $subject .= mb_convert_encoding(rtrim($obj->text, "t"), 'UTF-8', 'ISO-8859-1');

        return $subject;
    }
}

//creamos el objeto
$oObtieneMails = new ObtieneMails();

//ejecutamos el metodo
$oObtieneMails->obtenerAsuntosDelMails();
