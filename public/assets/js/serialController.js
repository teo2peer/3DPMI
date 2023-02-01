
async function startReader() {
    $("#info").html("Elige el puerto serie del lector de tarjetas");
    const port = await navigator.serial.requestPort({});
    await port.open({ baudRate: 9600 });
    const textDecoder = new TextDecoderStream();
    const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);
    const reader = textDecoder.readable.getReader();
    $("#info").html("Acerca la tarjeta en el lector");
    // read the arduino serial until end of line
    while (true) {
        const { value, done } = await reader.read();
        if (done) {
            // Allow the serial port to be closed later.
            reader.releaseLock();
            break;
        }
        if (value && value.length > 7) {
            console.log(value);
            pin = prompt("Tu tarjeta es: " + value + "Proporciona un pin por si no la llevas", "Harry Potter");
            // ajax call to the server
            $.ajax({
                url: "/dashboard/users/smartLogin/add",
                type: "POST",
                data: {
                    uid: value,
                    pin: pin,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    // succes icon in mainWrap
                    $('#mainWrap').html('<div class="alert alert-success" role="alert">Tarjeta añadida correctamente</div>');

                }, error: function (data) {
                    // error icon in mainWrap
                    alert("Error al añadir la tarjeta, vuelve a intentarlo");
                }

            });
            break;


        }
    }




}