<h1>Olá, {{ $user->name }}, espero que sim</h1>

<h3>Obrigado por sua inscrição</h3>

<p>
    Faça bom proveiro e excelentes compras em nosso marketplace!<br>
    Seu email de cadastro é: <strong>{{ $user->email }}
</p>
<hr>
Email enviado em {{ date('d/m/Y H:i:s') }}
