<?php

namespace App\Helpers;

use App\Models\Empresa;
use Carbon\Carbon;
use Nette\Utils\Random;

class FormatUtils
{
    public static function formatMoney($value)
    {
        return number_format((float) $value, 2, ',', '.');
    }

    public static function formatDate($date, $format = 'd/m/Y')
    {
        return date($format, strtotime($date));
    }

    public static function formatMoneyDb($value)
    {
        return floatval(str_replace(['.', ','], ['', '.'], $value));
    }

    public static function mesPorExtenso($data)
    {
        $dataCarbon = date('m', strtotime($data));
        $meses = [
            'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
            'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
        ];
        return $meses[$dataCarbon - 1];
    }

    public static function validateExtensionPdf($file)
    {
        return (substr($file, -3) === 'pdf' || substr($file, -3) === 'PDF') ? true : false;
    }

    public static function except($array, $keys)
    {
        foreach ($keys as $key) {
            unset($array[$key]);
        }
        return $array;
    }

    public static function validarData($data)
    {
        // Formato esperado: d/m/Y
        $pattern = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';

        if (preg_match($pattern, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public static function formatStrLog(string $text, string $process, array $variaveis)
    {
        $now = date('Y-m-d H:i:s');
        $formattedText = vsprintf($text, $variaveis);
        printf("[{$process}] {$now} - {$formattedText}" . PHP_EOL);
    }

    public static function traceCode($idEmpresa = null)
    {
        $idEmpresa = is_null($idEmpresa) ? auth()->user()->id_empresa : $idEmpresa;
        //$empresa = Empresa::find($idEmpresa);
        //$filial = '00';
        $random = Random::generate(11, '0-9');
        return $random; //substr($empresa->nome, 0, 2).$filial.strtoupper($random);
    }

    public static function now()
    {
        setlocale(LC_TIME, 'pt_BR.utf-8', 'pt_BR', 'Portuguese_Brazil');

        Carbon::setLocale('pt-BR');
        return Carbon::now();
    }

    public static function formatDoc($doc)
    {
        if ($doc <= 11) {
            $cpf = preg_replace('/[^0-9]/', '', $doc);
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
        }

        if ($doc > 11) {
            $cnpj = preg_replace('/[^0-9]/', '', $doc);
            // Adiciona os pontos, a barra e o traço
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
        }
    }

    public static function formatCep($cep)
    {
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
    }

    public static function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
    }
}
