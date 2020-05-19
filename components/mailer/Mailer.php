<?php

namespace rabint\components\mailer;

use Yii;
use yii\base\InvalidConfigException;
use yii\mail\BaseMailer;

/**
 * Mailer implements a mailer based on SwiftMailer.
 *
 * To use Mailer, you should configure it in the application configuration like the following,
 *
 * ~~~
 * 'components' => [
 *     ...
 *     'mailer' => [
 *         'class' => 'yii\swiftmailer\Mailer',
 *         'transport' => [
 *             'class' => 'Swift_SmtpTransport',
 *             'host' => 'localhost',
 *             'username' => 'username',
 *             'password' => 'password',
 *             'port' => '587',
 *             'encryption' => 'tls',
 *         ],
 *     ],
 *     ...
 * ],
 * ~~~
 *
 * You may also skip the configuration of the [[transport]] property. In that case, the default
 * PHP `mail()` function will be used to send emails.
 *
 * You specify the transport constructor arguments using 'constructArgs' key in the config.
 * You can also specify the list of plugins, which should be registered to the transport using
 * 'plugins' key. For example:
 *
 * ~~~
 * 'transport' => [
 *     'class' => 'Swift_SmtpTransport',
 *     'constructArgs' => ['localhost', 25]
 *     'plugins' => [
 *         [
 *             'class' => 'Swift_Plugins_ThrottlerPlugin',
 *             'constructArgs' => [20],
 *         ],
 *     ],
 * ],
 * ~~~
 *
 * To send an email, you may use the following code:
 *
 * ~~~
 * Yii::$app->mailer->compose('contact/html', ['contactForm' => $form])
 *     ->setFrom('from@domain.com')
 *     ->setTo($form->email)
 *     ->setSubject($form->subject)
 *     ->send();
 * ~~~
 *
 * @see http://swiftmailer.org
 *
 * @property array|\Swift_Mailer $swiftMailer Swift mailer instance or array configuration. This property is
 * read-only.
 * @property array|\Swift_Transport $transport This property is read-only.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class Mailer extends BaseMailer {

    public $messageClass = 'rabint\components\mailer\Message';
    public $_message = '';
    private $_transport = [];

    /**
     * @inheritdoc
     * @param Message $message Description
     */
    protected function sendMessage($message) {
        $config = [];
//        'host' => 'localhost',
//            'username' => 'rtv@rabint.ir',
//            'password' => 'D@elUOo?&DS4',
//            'port' => '25',

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.aqrazavi.org';
        $config['smtp_port'] = 25;
        $config['smtp_user'] = 'razavi.tv@aqrazavi.org';
        $config['smtp_pass'] = 'Mail_razavi.tv';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['validate'] = true;
        $ciMail = new CI_Email($config);

        $ciMail->set_newline("\r\n");
        $ciMail->from($message->getFrom());
        $ciMail->to($message->getTo());
        $ciMail->subject($message->getSubject());
        $ciMail->message($message->toString());

        if (!$ciMail->send()) {
            return false;
        }
        return true;
    }

    public function setTransport($transport) {
        if (!is_array($transport) && !is_object($transport)) {
            throw new InvalidConfigException('"' . get_class($this) . '::transport" should be either object or array, "' . gettype($transport) . '" given.');
        }
        $this->_transport = $transport;
    }

    /**
     * @return array|\Swift_Transport
     */
    public function getTransport() {
        if (!is_object($this->_transport)) {
            $this->_transport = $this->createTransport($this->_transport);
        }

        return $this->_transport;
    }

}
