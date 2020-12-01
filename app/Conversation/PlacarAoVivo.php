<?php

namespace App\Conversation;

use App\Models\Match;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade;

class PlacarAoVivo extends Conversation
{
    public function run()
    {
        $this->initial();
    }

    public function initial()
    {
        $crawler = GoutteFacade::request('GET',
            'https://www.futebolnatv.com.br/');

        $dados = $crawler->filter('.table-bordered')
            ->eq(0)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i){

                $tempo[$i] = $tr->filter('th')->filter('div')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });

                $horario[$i] = $tr->filter('th')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });
                $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });

                $time [$i] = $tr->filter('td')->filter('span')->each(function ($td) {
                    return trim($td->text());
                });

                //Filtrando só série A
                if( strpos($liga[$i][0], 'Série A') != false ) {
                    $placarTime1 = explode(" ", $liga[$i][1])[0];
                    $placarTime2 = explode(" ", $liga[$i][2])[0];
                    $time1 =isset($liga[$i][1])?explode(" ", $liga[$i][1]):'';
                    $time2 = isset($liga[$i][1])?explode(" ", $liga[$i][1]):'';

                    $dados['time1'] = isset($time1[1])? $time1[1] : '';
                    $dados['palcarTime1'] = isset($placarTime1)?(int)$placarTime1:'-';
                    $dados['time2'] = isset($time2[2])? $time2[2] : '';
                    $dados['palcarTime2'] = isset($placarTime2)?(int)$placarTime2:'-';
                    $dados['hora'] = $horario[$i][0];
                    $dados['tempo'] = isset($tempo[$i][0])?' - '.$tempo[$i][0]:'';
                    $dados['canal'] = isset($liga[$i][4])? $liga[$i][4] : '';
                    return $dados;
                }
            });

        $dados =  array_filter($dados);

        $date = Carbon::now()->format('d/m/Y');
        $firstPart = "\xF0\x9F\x9A\xA9	RESULTADOS AGORA - BRASILEIRÃO SÉRIE A - ".$date."\n";

        foreach ($dados as $jogo) {
            $firstPart = $firstPart. "\n \xE2\x9A\xBD : ". $jogo['time1'] .' '. $jogo['palcarTime1']." x ".
                    $jogo['palcarTime2'].' '.$jogo['time2'] ."\n"
                . " \xF0\x9F\x95\xA7 : ". $jogo['hora'].$jogo['tempo']."\n"
                . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                ."-------------------------------------------------------";
        }

        $this->say($firstPart);

        return true;
    }
}
