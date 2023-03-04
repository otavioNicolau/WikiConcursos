<?php

function getDefaultHeaders()
{
    //AJUSTEs
    $num = env('BOT');

    switch ($num) {
        case 1:
            //otavio.gatz
            return  [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071649',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=F28DD27F4149A04843462294D4B072FF; TecPermanecerLogado=ODcwNTMwLG90YXZpby5nYXR6QGdtYWlsLmNvbSwkMmEkMTIkQnZxbUI5LzVQMVlTZjBFL0c2UkxPLjNVRkQvazFOT0VSQUJqbDlvUzFKMkFmQUNBL0sxMDI=; _gat=1; _gat_UA-32462178-1=1; _ga=GA1.1.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677933476.24.1.1677933585.24.0.0; _gali=questoes-busca; AWSALB=3G0hpuotuNbltegL6BHJTq9ycJIuSKAeH0kfGjnXRMNxvf/fp0TZoTicb2YLsk2WdecscTy2c6uaxXgsg2LY5CO160FmFGyisHO8j+v7jxEYcui7LEeurlbRGzEi; AWSALBCORS=3G0hpuotuNbltegL6BHJTq9ycJIuSKAeH0kfGjnXRMNxvf/fp0TZoTicb2YLsk2WdecscTy2c6uaxXgsg2LY5CO160FmFGyisHO8j+v7jxEYcui7LEeurlbRGzEi',
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
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'         ];
        case 2:
            //otavio.nicolllau
            return  [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071649',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.922652971.1677191667; JSESSIONID=DFC718E046251C0A4DB8F9D8AE34C31B; TecPermanecerLogado="MzI3MjA2NixvdGF2aW8ubmljb2xsbGF1QGdtYWlsLmNvbSwkMmEkMTIkMzJlUXFDdHdZYVQ4Wjc0b2N4OFcwLnU2c3BzcjhGL1pORWVocHhsZTBRUW5RLzlQMWFaaUs="; _ga=GA1.1.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677800577.21.1.1677800636.1.0.0; _gali=questoes-busca; AWSALB=Aurk3UXlJk2jQL1/maCFil+ZOVL+fV8fGvPjbtEba2zL1lA2LKzZfMUWnwJAGaOMhjgnKv+LTY9Y2Pajubpqr3LPhsrMsN6IVn8h8Kq7BD6zWNnQiIARRUWJhXE6; AWSALBCORS=Aurk3UXlJk2jQL1/maCFil+ZOVL+fV8fGvPjbtEba2zL1lA2LKzZfMUWnwJAGaOMhjgnKv+LTY9Y2Pajubpqr3LPhsrMsN6IVn8h8Kq7BD6zWNnQiIARRUWJhXE6',
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

        case 3:
            //laura.nicolllau
            return [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071649',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=1FA7D80064D965FD5BFDD6BEC18FEE79; _gat=1; _gat_UA-32462178-1=1; TecPermanecerLogado=MzI3MzUxMyxsYXVyYS5uaWNvbGxsYXVAZ21haWwuY29tLCQyYSQxMiR0c0NqSkk4ZWVNMTl0T3lMWGZtWFkuaGNZc005NTloTExidVBKbmYyQVkydGdVaHFndjVRaQ==; _ga=GA1.3.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677889488.22.1.1677892134.24.0.0; _gali=questoes-busca; AWSALB=xg3S75FUA93uQiMKV5WD3rKi/q9MCF5QUs3CrCgboDceDdWijxZdM5MaZZSuZoa07NnY5ddgumQ4F+Gbzg83j0M1ixNC9+aEd2qr8L6DYxzVSfEwfJu8cBbYlQnN; AWSALBCORS=xg3S75FUA93uQiMKV5WD3rKi/q9MCF5QUs3CrCgboDceDdWijxZdM5MaZZSuZoa07NnY5ddgumQ4F+Gbzg83j0M1ixNC9+aEd2qr8L6DYxzVSfEwfJu8cBbYlQnN',
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
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'            ];
        case 4:
            //alice.nicolllau@gmail.com
            return [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071649',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=B6BB39FADB7AC5BF3A42E7A2157B1F78; _gat=1; _gat_UA-32462178-1=1; _ga=GA1.3.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677889488.22.1.1677891985.5.0.0; _gali=questoes-busca; AWSALB=uT54ndWCYQeQfyduKSiI0lvhYrYwDXtLWmDGKQdJbIvtv9r94c2NcuG6Ms25vAyAwsmlMbt36D9L24SUYvVgwDvkRnWvjYE4vB/z/pouRhgg4rLp3/34uraRqYwC; AWSALBCORS=uT54ndWCYQeQfyduKSiI0lvhYrYwDXtLWmDGKQdJbIvtv9r94c2NcuG6Ms25vAyAwsmlMbt36D9L24SUYvVgwDvkRnWvjYE4vB/z/pouRhgg4rLp3/34uraRqYwC',
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
        case 5:
            //rafael.nicolllau
            return [
              'authority' => 'www.tecconcursos.com.br',
              'method' => 'GET',
              'path' => '/api/questoes/2071649',
              'scheme' => 'https',
              'accept' => 'application/json, text/plain, * / *',
              'accept-encoding' => 'gzip, deflate, br',
              'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
              'cache-control' => 'no-cache',
              'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=14B407067CB86A9AC31D1DDE5E23E565; TecPermanecerLogado=MzI3MzE2NSxyYWZhZWwubmljb2xsbGF1QGdtYWlsLmNvbSwkMmEkMTIkd3NBY0N5U0NRUzMwdUtEUUpNOXpCT0J6TnB0SVN3bC90VUdzOXZ1bzBLd2VBbmYxYWRMUEs=; _gat=1; _ga=GA1.3.1154984221.1673711946; _gat_UA-32462178-1=1; _ga_1LNYCM2MLB=GS1.1.1677889488.22.1.1677889629.58.0.0; _gali=questoes-busca; AWSALB=M+VwFR/PFsp90ATk/3cu5f3vear3lgm6cgwhFonbD7rgmrwR08+h+/5WI/eeXTGfgDCzJJUMQLbAFbU1W8/W1Rvv7zZvcAx/5CzK/oM+Ar5Xk9jNifKFMY1O0i1g; AWSALBCORS=M+VwFR/PFsp90ATk/3cu5f3vear3lgm6cgwhFonbD7rgmrwR08+h+/5WI/eeXTGfgDCzJJUMQLbAFbU1W8/W1Rvv7zZvcAx/5CzK/oM+Ar5Xk9jNifKFMY1O0i1g',
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
}


function getDelay()
{
    return 05;
}

function getDelayQuestoes()
{
    
    return 9;
}

function getDelayProvas()
{
    return 9;
}

function getDelayComentarios()
{
    return 9;
}
function getDelayCargos()
{
    return 9;
}

function getDelayEditais()
{
    return 9;
}

function getDelayDownload()
{
    return 12;
}
function getDelayAssuntos()
{
    return 9;
}

function getDelayOrgaos()
{
    return 9;
}
