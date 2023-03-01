<?php

function getDefaultHeaders()
{
    return  [
        'authority' => 'www.tecconcursos.com.br',
        'path' => '/api/questoes/2248156',
        'scheme' => 'https',
        'accept' => 'application/json, text/plain, * / *',
        'accept-encoding' => 'gzip, deflate, br',
        'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
        'cache-control' => 'no-cache',
        'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.922652971.1677191667; JSESSIONID=F95E555BDBB6A789620C457D3245F43C; TecPermanecerLogado=ODcwNTMwLG90YXZpby5nYXR6QGdtYWlsLmNvbSwkMmEkMTIkQnZxbUI5LzVQMVlTZjBFL0c2UkxPLjNVRkQvazFOT0VSQUJqbDlvUzFKMkFmQUNBL0sxMDI=; _gat=1; _gat_UA-32462178-1=1; _ga=GA1.1.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677625218.16.1.1677627539.16.0.0; AWSALB=N+9dCjBAaTKa2NXaf5KrcCJmODVqPBO9J135f5DMmcoYi/RoWSYoaMLLRZBGQhg5mwvzUDwWlbD3e7Pf7dkpRSp6nKhVbOjgK5W0nZPBS92shIqWBMVlqbwBIIRm; AWSALBCORS=N+9dCjBAaTKa2NXaf5KrcCJmODVqPBO9J135f5DMmcoYi/RoWSYoaMLLRZBGQhg5mwvzUDwWlbD3e7Pf7dkpRSp6nKhVbOjgK5W0nZPBS92shIqWBMVlqbwBIIRm',
        'if-modified-since' => 'Mon, 26 Jul 1997 05:00:00 GMT',
        'logado' => 'true',
        'pragma' => 'no-cache',
        'referer' => 'https://www.tecconcursos.com.br/questoes/busca',
        'sec-ch-ua' => '"Chromium";v="110", "Not A(Brand";v="24", "Google Chrome";v="110"',
        'sec-ch-ua-mobile' => '?0',
        'sec-ch-ua-platform' => '"Windows"',
        'sec-fetch-dest' => 'empty',
        'sec-fetch-mode' => 'cors',
        'sec-fetch-site' => 'same-origin',
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'
      ];
}


function getDelay()
{
    return 5;
}

function getDelayQuestoes()
{
    return 10;
}

function getDelayProvas()
{
    return  10;
}

function getDelayComentarios()
{
    return  10;
}
function getDelayCargos()
{
    return  10;
}

function getDelayEditais()
{
    return  10;
}

function getDelayDownload()
{
    return  10;
}
function getDelayAssuntos()
{
    return  10;
}

function getDelayOrgaos()
{
    return  10;
}