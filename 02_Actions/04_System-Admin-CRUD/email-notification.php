<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Sends an HTML email using PHPMailer
 *
 * @param string $to_email    Recipient email address
 * @param string $to_name     Recipient name
 * @param string $subject     Email subject
 * @param string $body        HTML email body
 * @return bool               True if sent, false if failed
 */
function sendEmail(string $to_email, string $to_name, string $subject, string $body): bool {
    $mail = new PHPMailer(true);

    try {
        // ── SMTP Configuration ─────────────────────────────────
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'alquiencapuyan18@gmail.com';   // ← your Gmail
        $mail->Password   = 'rkbz bgup ypiq nwrm';          // ← your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPDebug  = 0;

        // ── SSL Options (for localhost) ─────────────────────────
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            ]
        ];

        // ── Sender & Recipient ─────────────────────────────────
        $mail->setFrom('alquiencapuyan18@gmail.com', 'MediFind');
        $mail->addAddress($to_email, $to_name);

        // ── Content ────────────────────────────────────────────
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}


/**
 * Email template for pharmacy approval notification
 *
 * @param string $owner_name    Pharmacy owner's name
 * @param string $pharmacy_name Pharmacy name
 * @param string $status        Approved / Rejected / Suspended
 */
function pharmacyApprovalEmail(string $owner_name, string $pharmacy_name, string $status): string {

    // ── Status-based styling ───────────────────────────────────
    $statusConfig = [
        'Approved'  => [
            'color'   => '#1d9e75',
            'bg'      => '#d4f5e2',
            'icon'    => '✅',
            'message' => 'Congratulations! Your pharmacy has been <strong>approved</strong>. You can now access your pharmacy dashboard and start managing your inventory.'
        ],
        'Rejected'  => [
            'color'   => '#c0392b',
            'bg'      => '#fde8e8',
            'icon'    => '❌',
            'message' => 'We regret to inform you that your pharmacy application has been <strong>rejected</strong>. Please update your information and resubmit your application.'
        ],
        'Suspended' => [
            'color'   => '#b45309',
            'bg'      => '#fff0e0',
            'icon'    => '⚠️',
            'message' => 'Your pharmacy account has been <strong>suspended</strong>. Please contact our support team for more information.'
        ],
    ];

    $config  = $statusConfig[$status] ?? $statusConfig['Approved'];
    $year    = date('Y');

    return "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pharmacy {$status} - MediFind</title>
    </head>
    <body style='margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif;'>

        <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f4f6f8; padding: 40px 0;'>
            <tr>
                <td align='center'>
                    <table width='600' cellpadding='0' cellspacing='0'
                        style='background:#ffffff; border-radius:16px; overflow:hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);'>

                        <!-- ── HEADER ───────────────────────────────── -->
                        <tr>
                            <td style='background-color:#1d9e75; padding: 32px; text-align:center;'>

                                <!-- LOGO PLACEHOLDER -->
                                <!-- Replace the img src below with your actual logo URL -->
                                <!-- Example: <img src='https://yourdomain.com/logo.png' ...> -->
                                <img src='https://via.placeholder.com/120x40/ffffff/1d9e75?text=MediFind'
                                    alt='MediFind Logo'
                                    style='height:40px; margin-bottom:12px;'>

                                <h1 style='color:#ffffff; margin:0; font-size:22px; font-weight:700;'>
                                    {$config['icon']} Pharmacy {$status}
                                </h1>
                            </td>
                        </tr>

                        <!-- ── BODY ─────────────────────────────────── -->
                        <tr>
                            <td style='padding: 36px 40px;'>

                                <!-- Greeting -->
                                <p style='font-size:16px; color:#1f2937; margin-bottom:8px;'>
                                    Hello, <strong>{$owner_name}</strong>!
                                </p>

                                <!-- Status Badge -->
                                <div style='display:inline-block; background:{$config['bg']}; color:{$config['color']};
                                    padding: 6px 16px; border-radius:20px; font-size:13px; font-weight:600;
                                    margin-bottom:20px;'>
                                    {$config['icon']} {$status}
                                </div>

                                <!-- Pharmacy Name -->
                                <div style='background:#f9fafb; border-left: 4px solid {$config['color']};
                                    padding: 16px 20px; border-radius:8px; margin-bottom:24px;'>
                                    <p style='margin:0; font-size:13px; color:#6b7280;'>Pharmacy Name</p>
                                    <p style='margin:4px 0 0; font-size:16px; font-weight:700; color:#111827;'>
                                        {$pharmacy_name}
                                    </p>
                                </div>

                                <!-- Message -->
                                <p style='font-size:15px; color:#374151; line-height:1.7; margin-bottom:24px;'>
                                    {$config['message']}
                                </p>

                                <!-- CTA Button (Approved only) -->
                                " . ($status === 'Approved' ? "
                                <div style='text-align:center; margin-bottom:28px;'>
                                    <a href='http://localhost/MediFind_RocketLabs/05_PharmacyAdmin/01_Dashboard.php'
                                        style='background-color:#1d9e75; color:#ffffff; padding:12px 32px;
                                        border-radius:32px; text-decoration:none; font-size:15px; font-weight:600;
                                        display:inline-block;'>
                                        Go to Dashboard →
                                    </a>
                                </div>" : "") . "

                                <!-- Divider -->
                                <hr style='border:none; border-top:1px solid #e5e7eb; margin:24px 0;'>

                                <!-- Footer note -->
                                <p style='font-size:12px; color:#9ca3af; text-align:center; margin:0;'>
                                    If you have questions, contact us at
                                    <a href='mailto:support@medifind.com' style='color:#1d9e75;'>support@medifind.com</a>
                                </p>

                            </td>
                        </tr>

                        <!-- ── FOOTER ────────────────────────────────── -->
                        <tr>
                            <td style='background:#f9fafb; padding:20px 40px; text-align:center;
                                border-top:1px solid #e5e7eb;'>
                                <p style='margin:0; font-size:12px; color:#9ca3af;'>
                                    © {$year} MediFind — Malaybalay Medicine Availability Checker <br>
                                    This is an automated message. Please do not reply.
                                </p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>

    </body>
    </html>
    ";
}