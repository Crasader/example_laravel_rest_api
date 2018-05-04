<html>
    <meta charset='utf-8' />
    <head>
        <title>@yield('title') Pdf</title>

        <link rel="stylesheet" href="{!! public_path('css/pdf-layout.css') !!}">
    </head>
    <body>
        <h2 class="document-header">@yield('document-header')</h2>
        <p>
            <em>
                <span class="user-short-information">@yield('user-short-information')</span>
            </em>
        </p>
        <p>
            Lorem ipsum dolor sit amet, mutat populo quaestio has ne, nam
            vidisse lucilius pericula ut, sit te denique adolescens. Mei unum
            vidit ad, cu mea bonorum fabellas. Consul democritum ut qui, albucius
            perpetua an qui, at qui adhuc inani appellantur. Delicata appellantur
            per ut. Modo brute dissentias eu usu, illud delenit splendide sea te.
        </p>
        <p>
            Meliore salutandi ad sit, in nam possim scriptorem. Aeque expetendis
            sed ei, viris vivendo et mei. Id expetendis consequuntur sed. Mei
            atqui denique vulputate ei, errem equidem maiorum ius in, nostrum
            omnesque delicata qui id. Usu fierent fastidii partiendo no, eruditi
            delenit ne mel. Eos eu dolor reprimique, vim ei purto erant propriae.
        </p>
        <p>
            Dicat facilis sea eu. Pri soleat adversarium ei, cibo meis temporibus
            eu vix. At mel scaevola tacimates convenire, ea wisi vulputate sit, vel
            munere putent delicatissimi ad. Sit in melius aperiam dissentias, mei soluta
            efficiendi ne.
        </p>
        <p>
            Tollit eloquentiam id eam, ei simul omnesque eam. Omnis posidonium
            ad pri, vis at adipisci scribentur. Salutandi sadipscing an eum,
            ex lucilius probatus dissentias vim, ne pri quot everti audire.
            Sea perfecto assentior tincidunt ut, purto graecis ne per, id
            albucius sapientem mediocritatem nec. In ius noster facete
            placerat, in audiam fabulas nec.
        </p>
        <p>
            Nam fugit timeam democritum cu, ei dolorum dolores persequeris
            has. Ei choro sapientem sit, cu esse erat his, nam ad commune
            iudicabit. Mea tale brute et, erant aperiam copiosae et duo,
            ubique mollis recusabo has no. Et debet doming gloriatur vel,
            cum in doming invidunt.
        </p>
        <h4>Lorem ipsum dolor sit amet:</h4>
        <ol>
            <li>usu id vero detracto</li>
            <li>cum cu vocent delectus delicata</li>
            <li>case phaedrum pri eu</li>
            <li>Mel consul viderer et</li>
            <li>nostrum ocurreret an</li>
            <li>Tempor partiendo adversarium</li>
        </ol>
        <h4>Custom part:</h4>
        <p class="custom-part">
            @yield('custom-part')
        </p>
    </body>
</html>
