<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Database fields:
 * @property integer  $social_id
 * @property integer  $settings_id
 * @property string   $social
 * @property string   $url
 * @property integer  $rank
 *
 * Defined relations:
 * @property Settings $settings
 *
 * Defined properties:
 * @property string   $socialText
 * @property string   $socialIco
 * @property string   $urlHtml
 */
class SettingsSocial extends ActiveRecord
{
    const SOCIAL_VK         = 'vkontakte';
    const SOCIAL_FACEBOOK   = 'facebook';
    const SOCIAL_TWITTER    = 'twitter';
    const SOCIAL_INSTAGRAM  = 'instagram';
    const SOCIAL_GOOGLE     = 'google';
    const SOCIAL_LINKEDIN   = 'linkedIn';
    const SOCIAL_REDDIT     = 'reddit';
    const SOCIAL_VIMEO      = 'vimeo';
    const SOCIAL_YAHOO      = 'yahoo';
    const SOCIAL_ADN        = 'adn';
    const SOCIAL_BITBUCKET  = 'bitbucket';
    const SOCIAL_TUMBLR     = 'tumblr';
    const SOCIAL_FLICKR     = 'flickr';
    const SOCIAL_SOUNDCLOUD = 'soundcloud';
    const SOCIAL_DROPBOX    = 'dropbox';
    const SOCIAL_FOURSQUARE = 'foursquare';
    const SOCIAL_GITHUB     = 'github';
    const SOCIAL_MICROSOFT  = 'microsoft';
    const SOCIAL_OPENID     = 'openId';
    const SOCIAL_EMAIL      = 'email';
    const SOCIAL_PHONE      = 'phone';
    const TITLE_VK         = 'ВКонтакте';
    const TITLE_FACEBOOK   = 'Facebook';
    const TITLE_TWITTER    = 'Twitter';
    const TITLE_INSTAGRAM  = 'Instagram';
    const TITLE_GOOGLE     = 'Google';
    const TITLE_LINKEDIN   = 'LinkedIn';
    const TITLE_REDDIT     = 'Reddit';
    const TITLE_VIMEO      = 'Vimeo';
    const TITLE_YAHOO      = 'Yahoo';
    const TITLE_ADN        = 'ADN';
    const TITLE_BITBUCKET  = 'Bitbucket';
    const TITLE_TUMBLR     = 'Tumblr';
    const TITLE_FLICKR     = 'Flickr';
    const TITLE_SOUNDCLOUD = 'SoundCloud';
    const TITLE_DROPBOX    = 'Dropbox';
    const TITLE_FOURSQUARE = 'Foursquare';
    const TITLE_GITHUB     = 'Github';
    const TITLE_MICROSOFT  = 'Microsoft';
    const TITLE_OPENID     = 'OpenId';
    const TITLE_EMAIL      = 'Email';
    const TITLE_PHONE      = 'Телефон';
    const ICO_VK         = 'vk';
    const ICO_FACEBOOK   = 'facebook';
    const ICO_TWITTER    = 'twitter';
    const ICO_INSTAGRAM  = 'instagram';
    const ICO_GOOGLE     = 'google-plus';
    const ICO_LINKEDIN   = 'linkedin';
    const ICO_VIMEO      = 'vimeo';
    const ICO_YAHOO      = 'yahoo';
    const ICO_ADN        = 'adn';
    const ICO_BITBUCKET  = 'bitbucket';
    const ICO_TUMBLR     = 'tumblr';
    const ICO_FLICKR     = 'flickr';
    const ICO_SOUNDCLOUD = 'soundcloud';
    const ICO_DROPBOX    = 'dropbox';
    const ICO_FOURSQUARE = 'foursquare';
    const ICO_GITHUB     = 'github';
    const ICO_MICROSOFT  = 'microsoft';
    const ICO_OPENID     = 'openid';
    const ICO_REDDIT     = 'reddit';
    const ICO_EMAIL      = 'envelope-o';
    const ICO_PHONE      = 'phone';
    public static $icoLabels = [
        self::SOCIAL_VK        => self::ICO_VK,
        self::SOCIAL_FACEBOOK  => self::ICO_FACEBOOK,
        self::SOCIAL_TWITTER   => self::ICO_TWITTER,
        self::SOCIAL_INSTAGRAM => self::ICO_INSTAGRAM,
        self::SOCIAL_GOOGLE    => self::ICO_GOOGLE,
        self::SOCIAL_LINKEDIN  => self::ICO_LINKEDIN,
        /*self::SOCIAL_VIMEO      => self::ICO_VIMEO,
        self::SOCIAL_YAHOO      => self::ICO_YAHOO,
        self::SOCIAL_ADN        => self::ICO_ADN,
        self::SOCIAL_BITBUCKET  => self::ICO_BITBUCKET,
        self::SOCIAL_TUMBLR     => self::ICO_TUMBLR,
        self::SOCIAL_FLICKR     => self::ICO_FLICKR,
        self::SOCIAL_SOUNDCLOUD => self::ICO_SOUNDCLOUD,
        self::SOCIAL_DROPBOX    => self::ICO_DROPBOX,
        self::SOCIAL_FOURSQUARE => self::ICO_FOURSQUARE,
        self::SOCIAL_GITHUB     => self::ICO_GITHUB,
        self::SOCIAL_MICROSOFT  => self::ICO_MICROSOFT,
        self::SOCIAL_OPENID     => self::ICO_OPENID,
        self::SOCIAL_REDDIT     => self::ICO_REDDIT,*/
        self::SOCIAL_EMAIL     => self::ICO_EMAIL,
        self::SOCIAL_PHONE     => self::ICO_PHONE
    ];
    public static $titleLabels = [
        self::SOCIAL_VK        => self::TITLE_VK,
        self::SOCIAL_FACEBOOK  => self::TITLE_FACEBOOK,
        self::SOCIAL_TWITTER   => self::TITLE_TWITTER,
        self::SOCIAL_INSTAGRAM => self::TITLE_INSTAGRAM,
        self::SOCIAL_GOOGLE    => self::TITLE_GOOGLE,
        self::SOCIAL_LINKEDIN  => self::TITLE_LINKEDIN,
        /*self::SOCIAL_VIMEO      => self::TITLE_VIMEO,
        self::SOCIAL_YAHOO      => self::TITLE_YAHOO,
        self::SOCIAL_ADN        => self::TITLE_ADN,
        self::SOCIAL_BITBUCKET  => self::TITLE_BITBUCKET,
        self::SOCIAL_TUMBLR     => self::TITLE_TUMBLR,
        self::SOCIAL_FLICKR     => self::TITLE_FLICKR,
        self::SOCIAL_SOUNDCLOUD => self::TITLE_SOUNDCLOUD,
        self::SOCIAL_DROPBOX    => self::TITLE_DROPBOX,
        self::SOCIAL_FOURSQUARE => self::TITLE_FOURSQUARE,
        self::SOCIAL_GITHUB     => self::TITLE_GITHUB,
        self::SOCIAL_MICROSOFT  => self::TITLE_MICROSOFT,
        self::SOCIAL_OPENID     => self::TITLE_OPENID,
        self::SOCIAL_REDDIT     => self::TITLE_REDDIT,*/
        self::SOCIAL_EMAIL     => self::TITLE_EMAIL,
        self::SOCIAL_PHONE     => self::TITLE_PHONE
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_social';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['settings_id', 'social', 'url'], 'required'],
            [['settings_id', 'rank'], 'integer'],
            //FIXME: не работает с DynamicForm
            /*[['url'], 'url', 'enableIDN' => true, 'defaultScheme' => 'http', 'validSchemes' => ['http', 'https'], 'when' => function() {
                return !in_array($this->social, [self::SOCIAL_EMAIL, self::SOCIAL_PHONE]);
            }],
            [['url'], 'email', 'when' => function() {
                return $this->social == self::SOCIAL_EMAIL;
            }],*/ [['url'], 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('common', 'Идентификатор'),
            'settings_id' => Yii::t('common', 'Настройки'),
            'social'      => Yii::t('common', 'Соц. сеть'),
            'url'         => Yii::t('common', 'Ссылка'),
            'rank'        => Yii::t('common', 'Ранг')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasOne(Settings::class, ['id' => 'settings_id']);
    }

    /**
     * @return string
     */
    public function getSocialText()
    {
        $labels = self::$titleLabels;
        if (isset($labels[$this->social])) {
            return $labels[$this->social];
        }

        return Yii::t('common', 'Неизвестная соц. сеть');
    }

    /**
     * @return string
     */
    public function getUrlHtml()
    {
        return ($this->social == self::SOCIAL_EMAIL ? 'mailto:' : ($this->social == self::SOCIAL_PHONE ? 'tel:' : '')) . $this->url;
    }

    /**
     * @return string
     */
    public function getSocialIco()
    {
        if ($this->social == self::SOCIAL_PHONE) return 'phone';
        if ($this->social == self::SOCIAL_EMAIL) return 'email';

        return 'address';
    }
}
