<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 7/29/18
 * Time: 2:27 PM
 */

namespace rabint\helpers;


class curl
{
    public static function optionsToStr($options)
    {
        if (!is_array($options)) {
            return print_r($options, true);
        }
        
          if (!collection::isAssoc($options)) {
            return print_r($options, true);
        }
        
        $optStr = static::optionStrings();
        $return = [];
        foreach ($options as $k => $opt) {
            $key = isset($optStr[$k]) ? $optStr[$k] : $k;
            $return[$key] = static::optionsToStr($opt);
        }
        return var_export($return, true);
    }

    public static function optionStrings()
    {
        return [
            151 => 'CURLOPT_SSH_AUTH_TYPES',
            141 => 'CURLOPT_CONNECT_ONLY',
            1048607 => 'CURLINFO_REDIRECT_URL',
            1048608 => 'CURLINFO_PRIMARY_IP',
            2097192 => 'CURLINFO_PRIMARY_PORT',
            1048617 => 'CURLINFO_LOCAL_IP',
            2097194 => 'CURLINFO_LOCAL_PORT',
            10100 => 'CURLOPT_SHARE',
            113 => 'CURLOPT_IPRESOLVE',
            0 => 'CURL_FNMATCHFUNC_MATCH',
            1 => 'CURLSSLOPT_ALLOW_BEAST',
            2 => 'CURLSSLOPT_NO_REVOKE',
            91 => 'CURLOPT_DNS_USE_GLOBAL_CACHE',
            92 => 'CURLOPT_DNS_CACHE_TIMEOUT',
            3 => 'CURL_RTSPREQ_ANNOUNCE',
            10001 => 'CURLOPT_FILE',
            10009 => 'CURLOPT_INFILE',
            14 => 'CURLE_FTP_WEIRD_227_FORMAT',
            10002 => 'CURLOPT_URL',
            10004 => 'CURLOPT_PROXY',
            41 => 'CURLE_FUNCTION_NOT_FOUND',
            42 => 'CURLE_ABORTED_BY_CALLBACK',
            10023 => 'CURLOPT_HTTPHEADER',
            43 => 'CURLE_BAD_FUNCTION_ARGUMENT',
            20056 => 'CURLOPT_PROGRESSFUNCTION',
            44 => 'CURLE_BAD_CALLING_ORDER',
            45 => 'CURLINFO_LASTONE',
            46 => 'CURLE_BAD_PASSWORD_ENTERED',
            47 => 'CURLE_TOO_MANY_REDIRECTS',
            48 => 'CURLOPT_DIRLISTONLY',
            50 => 'CURLOPT_APPEND',
            51 => 'CURLE_SSL_PEER_CERTIFICATE',
            161 => 'CURLOPT_POSTREDIR',
            -1 => 'CURLSSH_AUTH_DEFAULT',
            52 => 'CURLE_GOT_NOTHING',
            54 => 'CURLE_SSL_ENGINE_SETFAILED',
            10005 => 'CURLOPT_USERPWD',
            10006 => 'CURLOPT_PROXYUSERPWD',
            10007 => 'CURLOPT_RANGE',
            13 => 'CURLMOPT_MAX_TOTAL_CONNECTIONS',
            155 => 'CURLOPT_TIMEOUT_MS',
            10015 => 'CURLOPT_POSTFIELDS',
            10016 => 'CURLOPT_REFERER',
            10162 => 'CURLOPT_SSH_HOST_PUBLIC_KEY_MD5',
            10152 => 'CURLOPT_SSH_PUBLIC_KEYFILE',
            10153 => 'CURLOPT_SSH_PRIVATE_KEYFILE',
            10018 => 'CURLOPT_USERAGENT',
            10017 => 'CURLOPT_FTPPORT',
            85 => 'CURLOPT_FTP_USE_EPSV',
            19 => 'CURLE_FTP_COULDNT_RETR_FILE',
            20 => 'CURLE_FTP_WRITE_ERROR',
            21 => 'CURLE_FTP_QUOTE_ERROR',
            10022 => 'CURLOPT_COOKIE',
            96 => 'CURLOPT_COOKIESESSION',
            58 => 'CURLE_SSL_CERTPROBLEM',
            10025 => 'CURLOPT_SSLCERT',
            10026 => 'CURLOPT_KEYPASSWD',
            10029 => 'CURLOPT_WRITEHEADER',
            81 => 'CURLOPT_SSL_VERIFYHOST',
            10031 => 'CURLOPT_COOKIEFILE',
            32 => 'CURLAUTH_NTLM_WB',
            4 => 'CURL_RTSPREQ_SETUP',
            5 => 'CURL_RTSPREQ_PLAY',
            6 => 'CURL_RTSPREQ_PAUSE',
            33 => 'CURLE_HTTP_RANGE_ERROR',
            34 => 'CURLE_HTTP_POST_ERROR',
            10036 => 'CURLOPT_CUSTOMREQUEST',
            10037 => 'CURLOPT_STDERR',
            53 => 'CURLE_SSL_ENGINE_NOTFOUND',
            19913 => 'CURLOPT_RETURNTRANSFER',
            10028 => 'CURLOPT_QUOTE',
            10039 => 'CURLOPT_POSTQUOTE',
            10062 => 'CURLOPT_INTERFACE',
            10063 => 'CURLOPT_KRBLEVEL',
            61 => 'CURLE_BAD_CONTENT_ENCODING',
            69 => 'CURLOPT_FILETIME',
            20011 => 'CURLOPT_WRITEFUNCTION',
            20012 => 'CURLOPT_READFUNCTION',
            20079 => 'CURLOPT_HEADERFUNCTION',
            68 => 'CURLOPT_MAXREDIRS',
            71 => 'CURLOPT_MAXCONNECTS',
            72 => 'CURLOPT_CLOSEPOLICY',
            74 => 'CURLOPT_FRESH_CONNECT',
            75 => 'CURLOPT_FORBID_REUSE',
            10076 => 'CURLOPT_RANDOM_FILE',
            10077 => 'CURLOPT_EGDSOCKET',
            78 => 'CURLOPT_CONNECTTIMEOUT',
            156 => 'CURLOPT_CONNECTTIMEOUT_MS',
            64 => 'CURLPROTO_TELNET',
            10065 => 'CURLOPT_CAINFO',
            10097 => 'CURLOPT_CAPATH',
            10082 => 'CURLOPT_COOKIEJAR',
            10083 => 'CURLOPT_SSL_CIPHER_LIST',
            19914 => 'CURLOPT_BINARYTRANSFER',
            99 => 'CURLOPT_NOSIGNAL',
            101 => 'CURLOPT_PROXYTYPE',
            98 => 'CURLOPT_BUFFERSIZE',
            80 => 'CURLOPT_HTTPGET',
            84 => 'CURLOPT_HTTP_VERSION',
            10087 => 'CURLOPT_SSLKEY',
            10088 => 'CURLOPT_SSLKEYTYPE',
            10089 => 'CURLOPT_SSLENGINE',
            90 => 'CURLE_SSL_PINNEDPUBKEYNOTMATCH',
            10086 => 'CURLOPT_SSLCERTTYPE',
            27 => 'CURLE_OUT_OF_MEMORY',
            10102 => 'CURLOPT_ACCEPT_ENCODING',
            59 => 'CURLE_SSL_CIPHER',
            105 => 'CURLOPT_UNRESTRICTED_AUTH',
            106 => 'CURLOPT_FTP_USE_EPRT',
            121 => 'CURLOPT_TCP_NODELAY',
            10104 => 'CURLOPT_HTTP200ALIASES',
            107 => 'CURLOPT_HTTPAUTH',
            8 => 'CURL_RTSPREQ_GET_PARAMETER',
            -2 => 'CURLAUTH_ANYSAFE',
            111 => 'CURLOPT_PROXYAUTH',
            110 => 'CURLOPT_FTP_CREATE_MISSING_DIRS',
            10103 => 'CURLOPT_PRIVATE',
            2097154 => 'CURLINFO_HTTP_CODE',
            2097174 => 'CURLINFO_HTTP_CONNECTCODE',
            2097175 => 'CURLINFO_HTTPAUTH_AVAIL',
            2097176 => 'CURLINFO_PROXYAUTH_AVAIL',
            2097177 => 'CURLINFO_OS_ERRNO',
            2097178 => 'CURLINFO_NUM_CONNECTS',
            4194331 => 'CURLINFO_SSL_ENGINES',
            4194332 => 'CURLINFO_COOKIELIST',
            1048606 => 'CURLINFO_FTP_ENTRY_PATH',
            3145761 => 'CURLINFO_APPCONNECT_TIME',
            4194338 => 'CURLINFO_CERTINFO',
            2097187 => 'CURLINFO_CONDITION_UNMET',
            2097189 => 'CURLINFO_RTSP_CLIENT_CSEQ',
            2097191 => 'CURLINFO_RTSP_CSEQ_RECV',
            2097190 => 'CURLINFO_RTSP_SERVER_CSEQ',
            1048612 => 'CURLINFO_RTSP_SESSION_ID',
            1048577 => 'CURLINFO_EFFECTIVE_URL',
            2097163 => 'CURLINFO_HEADER_SIZE',
            2097164 => 'CURLINFO_REQUEST_SIZE',
            3145731 => 'CURLINFO_TOTAL_TIME',
            3145732 => 'CURLINFO_NAMELOOKUP_TIME',
            3145733 => 'CURLINFO_CONNECT_TIME',
            3145734 => 'CURLINFO_PRETRANSFER_TIME',
            3145735 => 'CURLINFO_SIZE_UPLOAD',
            3145736 => 'CURLINFO_SIZE_DOWNLOAD',
            3145737 => 'CURLINFO_SPEED_DOWNLOAD',
            3145738 => 'CURLINFO_SPEED_UPLOAD',
            2097166 => 'CURLINFO_FILETIME',
            2097165 => 'CURLINFO_SSL_VERIFYRESULT',
            3145743 => 'CURLINFO_CONTENT_LENGTH_DOWNLOAD',
            3145744 => 'CURLINFO_CONTENT_LENGTH_UPLOAD',
            3145745 => 'CURLINFO_STARTTRANSFER_TIME',
            1048594 => 'CURLINFO_CONTENT_TYPE',
            3145747 => 'CURLINFO_REDIRECT_TIME',
            2097172 => 'CURLINFO_REDIRECT_COUNT',
            1048597 => 'CURLINFO_PRIVATE',
            7 => 'CURL_RTSPREQ_TEARDOWN',
            9 => 'CURL_RTSPREQ_SET_PARAMETER',
            10 => 'CURL_RTSPREQ_RECORD',
            11 => 'CURL_RTSPREQ_RECEIVE',
            12 => 'CURLE_FTP_WEIRD_USER_REPLY',
            15 => 'CURLE_FTP_CANT_GET_HOST',
            16 => 'CURLAUTH_DIGEST_IE',
            17 => 'CURLE_FTP_COULDNT_SET_BINARY',
            18 => 'CURLE_FTP_PARTIAL_FILE',
            22 => 'CURLE_HTTP_RETURNED_ERROR',
            23 => 'CURLE_WRITE_ERROR',
            24 => 'CURLE_MALFORMAT_USER',
            25 => 'CURLE_FTP_COULDNT_STOR_FILE',
            26 => 'CURLE_READ_ERROR',
            28 => 'CURLE_OPERATION_TIMEDOUT',
            29 => 'CURLE_FTP_COULDNT_SET_ASCII',
            30 => 'CURLE_FTP_PORT_FAILED',
            31 => 'CURLE_FTP_COULDNT_USE_REST',
            35 => 'CURLE_SSL_CONNECT_ERROR',
            36 => 'CURLE_BAD_DOWNLOAD_RESUME',
            37 => 'CURLE_FILE_COULDNT_READ_FILE',
            38 => 'CURLE_LDAP_CANNOT_BIND',
            39 => 'CURLE_LDAP_SEARCH_FAILED',
            40 => 'CURLE_LIBRARY_NOT_FOUND',
            49 => 'CURLE_TELNET_OPTION_SYNTAX',
            55 => 'CURLE_SEND_ERROR',
            56 => 'CURLE_RECV_ERROR',
            57 => 'CURLE_SHARE_IN_USE',
            60 => 'CURLE_SSL_CACERT',
            62 => 'CURLE_LDAP_INVALID_URL',
            63 => 'CURLE_FILESIZE_EXCEEDED',
            129 => 'CURLOPT_FTPSSLAUTH',
            119 => 'CURLOPT_USE_SSL',
            138 => 'CURLOPT_FTP_FILEMETHOD',
            137 => 'CURLOPT_FTP_SKIP_PASV_IP',
            128 => 'CURLPROTO_LDAP',
            256 => 'CURLPROTO_LDAPS',
            512 => 'CURLPROTO_DICT',
            1024 => 'CURLPROTO_FILE',
            2048 => 'CURLPROTO_TFTP',
            218 => 'CURLOPT_SASL_IR',
            10221 => 'CURLOPT_DNS_INTERFACE',
            10222 => 'CURLOPT_DNS_LOCAL_IP4',
            10223 => 'CURLOPT_DNS_LOCAL_IP6',
            10220 => 'CURLOPT_XOAUTH2_BEARER',
            10224 => 'CURLOPT_LOGIN_OPTIONS',
            227 => 'CURLOPT_EXPECT_100_TIMEOUT_MS',
            226 => 'CURLOPT_SSL_ENABLE_ALPN',
            225 => 'CURLOPT_SSL_ENABLE_NPN',
            10230 => 'CURLOPT_PINNEDPUBLICKEY',
            10231 => 'CURLOPT_UNIX_SOCKET_PATH',
            232 => 'CURLOPT_SSL_VERIFYSTATUS',
            234 => 'CURLOPT_PATH_AS_IS',
            233 => 'CURLOPT_SSL_FALSESTART',
            237 => 'CURLOPT_PIPEWAIT',
            10235 => 'CURLOPT_PROXY_SERVICE_NAME',
            10236 => 'CURLOPT_SERVICE_NAME',
            67108864 => 'CURLPROTO_SMB',
            134217728 => 'CURLPROTO_SMBS',
            229 => 'CURLOPT_HEADEROPT',
            10228 => 'CURLOPT_PROXYHEADER',
            30010 => 'CURLMOPT_CHUNK_LENGTH_PENALTY_SIZE',
            30009 => 'CURLMOPT_CONTENT_LENGTH_PENALTY_SIZE',
            20014 => 'CURLMOPT_PUSHFUNCTION',
            112 => 'CURLOPT_FTP_RESPONSE_TIMEOUT',
            10203 => 'CURLOPT_RESOLVE',
            160 => 'CURLOPT_NEW_DIRECTORY_PERMS',
            159 => 'CURLOPT_NEW_FILE_PERMS',
            10118 => 'CURLOPT_NETRC_FILE',
            10093 => 'CURLOPT_PREQUOTE',
            114 => 'CURLOPT_MAXFILESIZE',
            10134 => 'CURLOPT_FTP_ACCOUNT',
            10135 => 'CURLOPT_COOKIELIST',
            139 => 'CURLOPT_LOCALPORT',
            140 => 'CURLOPT_LOCALPORTRANGE',
            10147 => 'CURLOPT_FTP_ALTERNATIVE_TO_USER',
            150 => 'CURLOPT_SSL_SESSIONID_CACHE',
            154 => 'CURLOPT_FTP_SSL_CCC',
            158 => 'CURLOPT_HTTP_CONTENT_DECODING',
            157 => 'CURLOPT_HTTP_TRANSFER_DECODING',
            166 => 'CURLOPT_PROXY_TRANSFER_MODE',
            171 => 'CURLOPT_ADDRESS_SCOPE',
            10169 => 'CURLOPT_CRLFILE',
            10170 => 'CURLOPT_ISSUERCERT',
            10173 => 'CURLOPT_USERNAME',
            10174 => 'CURLOPT_PASSWORD',
            10175 => 'CURLOPT_PROXYUSERNAME',
            10176 => 'CURLOPT_PROXYPASSWORD',
            10177 => 'CURLOPT_NOPROXY',
            180 => 'CURLOPT_SOCKS5_GSSAPI_NEC',
            10179 => 'CURLOPT_SOCKS5_GSSAPI_SERVICE',
            178 => 'CURLOPT_TFTP_BLKSIZE',
            10183 => 'CURLOPT_SSH_KNOWNHOSTS',
            188 => 'CURLOPT_FTP_USE_PRET',
            10186 => 'CURLOPT_MAIL_FROM',
            10187 => 'CURLOPT_MAIL_RCPT',
            193 => 'CURLOPT_RTSP_CLIENT_CSEQ',
            194 => 'CURLOPT_RTSP_SERVER_CSEQ',
            10190 => 'CURLOPT_RTSP_SESSION_ID',
            10191 => 'CURLOPT_RTSP_STREAM_URI',
            10192 => 'CURLOPT_RTSP_TRANSPORT',
            189 => 'CURLOPT_RTSP_REQUEST',
            136 => 'CURLOPT_IGNORE_CONTENT_LENGTH',
            207 => 'CURLOPT_TRANSFER_ENCODING',
            10211 => 'CURLOPT_DNS_SERVERS',
            10070 => 'CURLOPT_TELNETOPTIONS',
            77 => 'CURLE_SSL_CACERT_BADFILE',
            79 => 'CURLE_SSH',
            268435457 => 'CURL_WRITEFUNC_PAUSE',
            4096 => 'CURLPROTO_IMAP',
            8192 => 'CURLPROTO_IMAPS',
            16384 => 'CURLPROTO_POP3',
            32768 => 'CURLPROTO_POP3S',
            262144 => 'CURLPROTO_RTSP',
            65536 => 'CURL_VERSION_HTTP2',
            131072 => 'CURLPROTO_SMTPS',
            20200 => 'CURLOPT_FNMATCH_FUNCTION',
            197 => 'CURLOPT_WILDCARDMATCH',
            524288 => 'CURLPROTO_RTMP',
            2097152 => 'CURLPROTO_RTMPE',
            8388608 => 'CURLPROTO_RTMPS',
            1048576 => 'CURLPROTO_RTMPT',
            4194304 => 'CURLPROTO_RTMPTE',
            16777216 => 'CURLPROTO_RTMPTS',
            33554432 => 'CURLPROTO_GOPHER',
            2147483648 => 'CURLAUTH_ONLY',
            10205 => 'CURLOPT_TLSAUTH_PASSWORD',
            10206 => 'CURLOPT_TLSAUTH_TYPE',
            10204 => 'CURLOPT_TLSAUTH_USERNAME',
            210 => 'CURLOPT_GSSAPI_DELEGATION',
            212 => 'CURLOPT_ACCEPTTIMEOUT_MS',
            10217 => 'CURLOPT_MAIL_AUTH',
            216 => 'CURLOPT_SSL_OPTIONS',
            213 => 'CURLOPT_TCP_KEEPALIVE',
            214 => 'CURLOPT_TCP_KEEPIDLE',
            215 => 'CURLOPT_TCP_KEEPINTVL',
            10238 => 'CURLOPT_DEFAULT_PROTOCOL',
            239 => 'CURLOPT_STREAM_WEIGHT',
            242 => 'CURLOPT_TFTP_NO_OPTIONS',
            10243 => 'CURLOPT_CONNECT_TO',
            244 => 'CURLOPT_TCP_FASTOPEN',
        ];
    }
}
