<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Mailer
{
    /**
     * Envia o alerta de item crítico pro gestor. Retorna [sucesso(bool), erro(?string)].
     */
    public static function enviarAlertaCritico(array $checklist, array $itensCriticos): array
    {
        if (MAIL_TO === '') {
            return [false, 'MAIL_TO não configurado no .env'];
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->Port = MAIL_PORT;
            if (MAIL_USER !== '') {
                $mail->SMTPAuth = true;
                $mail->Username = MAIL_USER;
                $mail->Password = MAIL_PASS;
            } else {
                $mail->SMTPAuth = false;
            }
            if (MAIL_ENCRYPTION !== '') {
                $mail->SMTPSecure = MAIL_ENCRYPTION;
            }
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NOME);
            $mail->addAddress(MAIL_TO);

            $mail->Subject = "[ALERTA] Item crítico no checklist - Placa {$checklist['placa']}";

            $linhas = [];
            $linhas[] = "Checklist #{$checklist['id']} — Placa {$checklist['placa']}";
            $linhas[] = 'Motorista: ' . ($checklist['motorista_nome'] ?? '-');
            $linhas[] = '';
            $linhas[] = 'Itens críticos:';
            foreach ($itensCriticos as $item) {
                $extra = !empty($item['classificacao'])
                    ? " [{$item['classificacao']}, ~{$item['vida_util_percentual']}% de vida útil]"
                    : '';
                $linhas[] = "- {$item['item_nome']}{$extra}: {$item['observacao_ia']}";
            }
            $linhas[] = '';
            $linhas[] = 'Acesse o painel: ' . APP_URL . '/painel/detalhe.php?id=' . $checklist['id'];

            $mail->Body = implode("\n", $linhas);
            $mail->send();
            return [true, null];
        } catch (PHPMailerException|Exception $e) {
            error_log('Falha ao enviar e-mail de alerta: ' . $mail->ErrorInfo);
            return [false, $mail->ErrorInfo ?: $e->getMessage()];
        }
    }
}
