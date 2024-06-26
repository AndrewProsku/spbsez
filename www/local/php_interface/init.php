<?php

use Kelnik\Multisites\Settings\CurrentSite;

include realpath(__DIR__ . '/../../../vendor/autoload.php');

if (!function_exists('getSiteBaseUrl')) {
    function getSiteBaseUrl()
    {
        return empty($_SERVER)
            ? ''
            : (($_SERVER['SERVER_PORT'] == 443 || strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http') .
                '://' . $_SERVER['HTTP_HOST'];
    }
}

function custom_mail($to, $subject, $message, $additionalHeaders = '', $additionalParameters = '', $context = null)
{
    $phpMailer = new \PHPMailer\PHPMailer\PHPMailer(true);

    $phpMailer->isHTML(true);
    $phpMailer->addAddress($to);
    $phpMailer->Subject = $subject;
    $phpMailer->Body = $message;
    $phpMailer->CharSet = 'utf-8';
    $phpMailer->XMailer = 'SEZ mailer';

    preg_match_all('!(From: (?P<from>[^\n]+))|(BCC: (?P<bcc>[^\n]+))|(CC: (?P<cc>[^\n]+))!i', $additionalHeaders, $matches);

    $matchesCnt = count($matches['from']) - 1;

    $phpMailer->setFrom(array_shift($matches['from']));

    if (!empty($matches['cc'][$matchesCnt])) {
        $phpMailer->addCC($matches['cc'][$matchesCnt]);
    }

    if (!empty($matches['bcc'][$matchesCnt])) {
        $phpMailer->addBCC($matches['bcc'][$matchesCnt]);
    }

    if (\Bitrix\Main\Loader::includeModule('kelnik.multisites')) {
        $curSite = CurrentSite::getInstance();
        $curSite->getData();

        if ($curSite->getField('USE_SMTP') === \Kelnik\Multisites\Settings\SitesTable::YES) {
            $phpMailer->isSMTP();
            $phpMailer->Port = 465;
            $phpMailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
            $phpMailer->Host = $curSite->getField('SMTP_HOST');
            $phpMailer->SMTPAuth = true;
            $phpMailer->Username = $curSite->getField('SMTP_USER');
            $phpMailer->Password = $curSite->getField('SMTP_PWD');
        }
    }

    try {
        $phpMailer->send();
    } catch (Exception $e) {
        return false;
    }

    return true;
}

class SezLang
{
    public const CHINESE_DIR = '/ch/';
    public const ENGLISH_DIR = '/en/';
    public const RUSSIAN_DIR = '/';

    public static function getDirBySite($siteId)
    {
        $res = \Bitrix\Main\SiteTable::getById($siteId)->fetch();

        return isset($res['DIR']) ? $res['DIR'] : '/';
    }
}

function pre($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}