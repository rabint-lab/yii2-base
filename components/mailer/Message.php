<?php

namespace rabint\components\mailer;

use yii\mail\BaseMessage;

class Message extends BaseMessage {

    /**
     * @var \Swift_Message Swift message instance.
     */
    private $_message;
    private $_charset;
    private $_from;
    private $_replyTo;
    private $_to;
    private $_cc;
    private $_bcc;
    private $_subject;
    private $_textBody;
    private $_htmlBody;
    private $_body;
    private $_contentType;

    public function getMessage() {
        if (!is_object($this->_message)) {
            $this->_message = $this->createMessage();
        }

        return $this->_message;
    }

    public function getCharset() {
        return $this->_charset;
    }

    public function setCharset($charset) {
        $this->_charset = $charset;
        return $this;
    }

    public function getFrom() {
        return $this->_from;
    }

    public function setFrom($from) {
        $this->_from = $from;
        return $this;
    }

    public function getReplyTo() {
        return $this->_replyTo;
    }

    /**
     * @inheritdoc
     */
    public function setReplyTo($replyTo) {
        $this->_replyTo = $replyTo;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTo() {
        return $this->_to;
    }

    /**
     * @inheritdoc
     */
    public function setTo($to) {
        $this->_to = $to;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCc() {
        return $this->_cc;
    }

    /**
     * @inheritdoc
     */
    public function setCc($cc) {
        $this->_cc = $cc;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBcc() {
        return $this->_bcc;
    }

    /**
     * @inheritdoc
     */
    public function setBcc($bcc) {
        $this->_bcc = $bcc;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSubject() {
        return $this->_subject;
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject) {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTextBody($text) {
        $this->_textBody = $text;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHtmlBody($html) {
         $this->_htmlBody = $html;
        return $this;
    }

    /**
     * Sets the message body.
     * If body is already set and its content type matches given one, it will
     * be overridden, if content type miss match the multipart message will be composed.
     * @param string $body body content.
     * @param string $contentType body content type.
     */
    protected function setBody($body, $contentType) {
        $this->_body = $body;
        $this->_contentType = $contentType;
//        $message = $this->getMessage();
//        $oldBody = $message->getBody();
//        $charset = $message->getCharset();
//        if (empty($oldBody)) {
//            $parts = $message->getChildren();
//            $partFound = false;
//            foreach ($parts as $key => $part) {
//                if (!($part instanceof \Swift_Mime_Attachment)) {
//                    /* @var $part \Swift_Mime_MimePart */
//                    if ($part->getContentType() == $contentType) {
//                        $charset = $part->getCharset();
//                        unset($parts[$key]);
//                        $partFound = true;
//                        break;
//                    }
//                }
//            }
//            if ($partFound) {
//                reset($parts);
//                $message->setChildren($parts);
//                $message->addPart($body, $contentType, $charset);
//            } else {
//                $message->setBody($body, $contentType);
//            }
//        } else {
//            $oldContentType = $message->getContentType();
//            if ($oldContentType == $contentType) {
//                $message->setBody($body, $contentType);
//            } else {
//                $message->setBody(null);
//                $message->setContentType(null);
//                $message->addPart($oldBody, $oldContentType, $charset);
//                $message->addPart($body, $contentType, $charset);
//            }
//        }
    }

    /**
     * @inheritdoc
     */
    public function attach($fileName, array $options = []) {
//        $attachment = \Swift_Attachment::fromPath($fileName);
//        if (!empty($options['fileName'])) {
//            $attachment->setFilename($options['fileName']);
//        }
//        if (!empty($options['contentType'])) {
//            $attachment->setContentType($options['contentType']);
//        }
//        $this->getMessage()->attach($attachment);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attachContent($content, array $options = []) {
//        $attachment = \Swift_Attachment::newInstance($content);
//        if (!empty($options['fileName'])) {
//            $attachment->setFilename($options['fileName']);
//        }
//        if (!empty($options['contentType'])) {
//            $attachment->setContentType($options['contentType']);
//        }
//        $this->getMessage()->attach($attachment);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function embed($fileName, array $options = []) {
//        $embedFile = \Swift_EmbeddedFile::fromPath($fileName);
//        if (!empty($options['fileName'])) {
//            $embedFile->setFilename($options['fileName']);
//        }
//        if (!empty($options['contentType'])) {
//            $embedFile->setContentType($options['contentType']);
//        }

        return $this;//->getMessage()->embed($embedFile);
    }

    /**
     * @inheritdoc
     */
    public function embedContent($content, array $options = []) {
//        $embedFile = \Swift_EmbeddedFile::newInstance($content);
//        if (!empty($options['fileName'])) {
//            $embedFile->setFilename($options['fileName']);
//        }
//        if (!empty($options['contentType'])) {
//            $embedFile->setContentType($options['contentType']);
//        }

        return $this;//->getMessage()->embed($embedFile);
    }

    /**
     * @inheritdoc
     */
    public function toString() {
        return $this->_body;
    }

    /**
     * Creates the Swift email message instance.
     * @return \Swift_Message email message instance.
     */
    protected function createMessage() {
        return new Message;
    }

}
