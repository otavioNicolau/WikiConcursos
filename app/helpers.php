<?php

function getDefaultHeaders()
{
    $num = rand(1, 5);

    switch ($num) {
        case 1:
            echo "otavio.gatz - ";
            return  [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071100',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; _gat=1; _gat_UA-32462178-1=1; JSESSIONID=31C708D17DB2CEA972F5A5531778CD28; TecPermanecerLogado=ODcwNTMwLG90YXZpby5nYXR6QGdtYWlsLmNvbSwkMmEkMTIkQnZxbUI5LzVQMVlTZjBFL0c2UkxPLjNVRkQvazFOT0VSQUJqbDlvUzFKMkFmQUNBL0sxMDI=; _ga=GA1.3.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677954587.26.1.1677955652.13.0.0; _gali=questoes-busca; AWSALB=uugJkgD0Z/vpZ1tpHx/TQs6Oim1BQQDM1IUZJm9dZOa9UQRYpHDHhSpxenoPALdulKWM5V+geCxc/TOG8I2839kaFEJ+JJd4y4oVDcfBccrAmaDERHCQphR36V5f; AWSALBCORS=uugJkgD0Z/vpZ1tpHx/TQs6Oim1BQQDM1IUZJm9dZOa9UQRYpHDHhSpxenoPALdulKWM5V+geCxc/TOG8I2839kaFEJ+JJd4y4oVDcfBccrAmaDERHCQphR36V5f',
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
        case 2:
            echo "otavio.nicolllau - ";
            return  [
                 'authority' => 'www.tecconcursos.com.br',
            'method' => 'GET',
            'path' => '/api/questoes/2071101',
            'scheme' => 'https',
            'accept' => 'application/json, text/plain, * / *',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
            'cache-control' => 'no-cache',
            'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=10630EACBE1499BEEDB6A43F9B420EFA; _gat=1; _gat_UA-32462178-1=1; TecPermanecerLogado=MzI3MjA2NixvdGF2aW8ubmljb2xsbGF1QGdtYWlsLmNvbSwkMmEkMTIkMzJlUXFDdHdZYVQ4Wjc0b2N4OFcwLnU2c3BzcjhGL1pORWVocHhsZTBRUW5RLzlQMWFaaUs=; _ga=GA1.3.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677954587.26.1.1677955708.35.0.0; _gali=questoes-busca; AWSALB=/K0tlVQDZ70FXvJZDAUySxK+zylsjtFxMbARnRIPIG785WKbvQN3GpngT4VNjcz2Ln3lDvfzRlDOz21ShxqB4pc+vJR1UrdkWA5D3lkQQ0jBkJOVVCO0RIOpB+OK; AWSALBCORS=/K0tlVQDZ70FXvJZDAUySxK+zylsjtFxMbARnRIPIG785WKbvQN3GpngT4VNjcz2Ln3lDvfzRlDOz21ShxqB4pc+vJR1UrdkWA5D3lkQQ0jBkJOVVCO0RIOpB+OK',
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
            echo "laura.nicolllau - ";
            return [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071102',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=2C7CC42721363599840EB0178E3AC58A; _gat=1; _gat_UA-32462178-1=1; TecPermanecerLogado=MzI3MzUxMyxsYXVyYS5uaWNvbGxsYXVAZ21haWwuY29tLCQyYSQxMiR0c0NqSkk4ZWVNMTl0T3lMWGZtWFkuaGNZc005NTloTExidVBKbmYyQVkydGdVaHFndjVRaQ==; _ga=GA1.1.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677954587.26.1.1677955776.35.0.0; _gali=questoes-busca; AWSALB=FamY1+2RwaFgtH+ft7B6ydU8ENrVR1X1C84khocod/nEHLQj2bwPGbByQ0YdcyvGnWXig8+Nckli4UrlsJr+9B0Kap/CBGwf2Qvw+myvmUr2hOJseTgP3ZtG+LSo; AWSALBCORS=FamY1+2RwaFgtH+ft7B6ydU8ENrVR1X1C84khocod/nEHLQj2bwPGbByQ0YdcyvGnWXig8+Nckli4UrlsJr+9B0Kap/CBGwf2Qvw+myvmUr2hOJseTgP3ZtG+LSo',
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
        case 4:
            echo "alice.nicolllau - ";
            return [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071103',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=330BB44DD5963C4420F384A0E239FCE5; _gat=1; _gat_UA-32462178-1=1; TecPermanecerLogado=MzI3MzUyNSxhbGljZS5uaWNvbGxsYXVAZ21haWwuY29tLCQyYSQxMiRsS1VaU3NYN0FGUkVIR2k1OXI2b2MuWWFTZ2dvWGlydWtVbkVsM05EM0hiZzFBYVpzb2guZQ==; _ga=GA1.3.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677954587.26.1.1677955834.39.0.0; _gali=questoes-busca; AWSALB=UCq4G13CQfdbLZ5+RkXpGVkbzyG6usYAAFbkZYyj+Q/ZDW1vvV7g8MwgilNd68BCuv3xs3Wwd1cvAPqpoD64xnl7cMxU22mJc+w5CnF3XVHGN5efHBvlP9OEjBsN; AWSALBCORS=UCq4G13CQfdbLZ5+RkXpGVkbzyG6usYAAFbkZYyj+Q/ZDW1vvV7g8MwgilNd68BCuv3xs3Wwd1cvAPqpoD64xnl7cMxU22mJc+w5CnF3XVHGN5efHBvlP9OEjBsN',
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
            echo "rafael.nicolllau - ";
            return [
                'authority' => 'www.tecconcursos.com.br',
                'method' => 'GET',
                'path' => '/api/questoes/2071104',
                'scheme' => 'https',
                'accept' => 'application/json, text/plain, * / *',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'no-cache',
                'cookie' => '_fbp=fb.2.1673711946824.1020756054; _gcl_au=1.1.984240383.1676984838; _gid=GA1.3.1485840514.1677889487; JSESSIONID=0B6FFDD5FEF5910F5FF507F09FDEF420; TecPermanecerLogado=ODcwNTMwLG90YXZpby5nYXR6QGdtYWlsLmNvbSwkMmEkMTIkQnZxbUI5LzVQMVlTZjBFL0c2UkxPLjNVRkQvazFOT0VSQUJqbDlvUzFKMkFmQUNBL0sxMDI=; _ga=GA1.3.1154984221.1673711946; _ga_1LNYCM2MLB=GS1.1.1677954587.26.1.1677954643.4.0.0; _gali=questoes-busca; AWSALB=yFu3rE27YN1LSLNuLRCZ199OZ07BNMbl0cv5rOAI650timRpV4FXLJGGwdCU1hpp14FC95LLlHROXbv/7VpPUkLS1cQZuO4HdCC1BTGWduFwHxlKANiIviyokwWw; AWSALBCORS=yFu3rE27YN1LSLNuLRCZ199OZ07BNMbl0cv5rOAI650timRpV4FXLJGGwdCU1hpp14FC95LLlHROXbv/7VpPUkLS1cQZuO4HdCC1BTGWduFwHxlKANiIviyokwWw',
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
