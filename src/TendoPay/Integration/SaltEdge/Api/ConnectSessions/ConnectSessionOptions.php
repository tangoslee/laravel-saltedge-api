<?php


/**
 * Class ConnectSessionOptions
 */
class ConnectSessionOptions
{
    private $options = [];

    /**
     * Private constructor.
     */
    private function __construct()
    {
    }

    /**
     * Singleton builder
     * @return static
     */
    public static function builder(): self
    {
        return new self();
    }

    /**
     * $customer_id string, required
     *  the ID of the customer received from customer create. This field is optional for ‘app’ authentication.
     *
     * @param $customerId
     * @return $this
     */
    public function set($customerId): self
    {
        $this->options['customer_id'] = $customerId;
        return self;
    }

    /**
     * $consent object, required
     *  the consent object
     *
     * @param $consent
     * @return $this
     */
    public function setConsent($consent): self
    {
        $this->options['consent'] = $consent;
        return self;
    }

    /**
     * $attempt object, optional
     *  the attempt object
     *
     * @param $attempt
     * @return $this
     */
    public function setAttempt($attempt): self
    {
        $this->options['attempt'] = $attempt;
        return self;
    }

    /**
     * $allowed_countries array of strings, optional
     *  the list of countries that will be accessible in Salt Edge Connect, defaults to null. Example: ['US', 'DE']
     *
     * @param $allowedCountries
     * @return $this
     */
    public function setAllowedCountries($allowedCountries): self
    {
        $this->options['allowed_countries'] = $allowedCountries;
        return self;
    }

    /**
     * $provider_code string, optional
     *  the code of the desired provider, defaults to null
     *
     * @param $providerCode
     * @return $this
     */
    public function setProviderCode($providerCode): self
    {
        $this->options['provider_code'] = $providerCode;
        return self;
    }

    /**
     * $daily_refresh boolean, optional
     *  whether the connection should be automatically refreshed by Salt Edge, defaults to false
     *
     * @param $dailyRefresh
     * @return $this
     */
    public function setDailyRefresh($dailyRefresh): self
    {
        $this->options['daily_refresh'] = $dailyRefresh;
        return self;
    }

    /**
     * $disable_provider_search boolean, optional
     *  whether the provider search will be disabled, works only if provider_code parameter is sent. Defaults to false
     *
     * @param $disableProviderSearch
     * @return $this
     */
    public function setDisableProviderSearch($disableProviderSearch): self
    {
        $this->options['disable_provider_search'] = $disableProviderSearch;
        return self;
    }

    /**
     * $return_connection_id boolean, optional
     *  whether to append connection_id to return_to URL. Defaults to false
     *
     * @param $returnConnectionId
     * @return $this
     */
    public function setReturnConnectionId($returnConnectionId): self
    {
        $this->options['return_connection_id'] = $returnConnectionId;
        return self;
    }

    /**
     * $provider_modes array of strings, optional
     *  restrict the list of the providers to only the ones that have the mode included in the array.
     *  Possible values inside the array are: oauth, web, api, file,
     *  defaults to the array containing all possible modes
     *
     * @param $providerModes
     * @return $this
     */
    public function setProviderModes($providerModes): self
    {
        $this->options['provider_modes'] = $providerModes;
        return self;
    }

    /**
     * $categorization string, optional
     *  the type of categorization applied.
     *  Possible values: none, personal, business.
     *  Default: personal
     *
     * @param $categorization
     * @return $this
     */
    public function setCategorization($categorization): self
    {
        $this->options['categorization'] = $categorization;
        return self;
    }

    /**
     * $javascript_callback_type string, optional
     *  allows you to specify what kind of callback type you are expecting.
     *  Possible values: iframe, external_saltbridge, external_notify, post_message.
     *  Defaults to null, which means that you will not receive any callbacks.
     *
     * @param $javascriptCallbackType
     * @return $this
     */
    public function setJavascriptCallbackType($javascriptCallbackType): self
    {
        $this->options['javascript_callback_type'] = $javascriptCallbackType;
        return self;
    }

    /**
     * $include_fake_providers boolean, optional
     *  being live, the customer will not be able to create fake provider connections. This flag allows it; if sent as true the customer will have the possibility to create any available fake provider connections. Defaults to false
     *
     * @param $includeFakeProviders
     * @return $this
     */
    public function setIncludeFakeProviders($includeFakeProviders): self
    {
        $this->options['include_fake_providers'] = $includeFakeProviders;
        return self;
    }

    /**
     * $lost_connection_notify  boolean, optional
     *  being sent as true, enables you to receive a javascript callback
     *  whenever the internet connection is lost during the fetching process.
     *  The type of the callback depends on the javascript_callback_type you specified.
     *  Defaults to false.
     *
     *  It has the following payload:
     *  {data: {error_class: 'ConnectionLost', error_message: 'Internet connection was lost.'}}
     *
     * @param $lostConnectionNotify
     * @return $this
     */
    public function setLostConnectionNotify($lostConnectionNotify): self
    {
        $this->options['lost_connection_notify'] = $lostConnectionNotify;
        return self;
    }


    /**
     * $show_consent_confirmation boolean, optional
     *  if Consent Confirmation is handled on the client’s side,
     *  this parameter should be sent as false so, upon submitting the form,
     *  the user will not be asked to give his consent to Salt Edge Inc.
     *
     *  Default value: true.
     *
     * @param $showConsentConfirmation
     * @return $this
     */
    public function setShowConsentConfirmation($showConsentConfirmation): self
    {
        $this->options['show_consent_confirmation'] = $showConsentConfirmation;
        return self;
    }


    /**
     * $credentials_strategy string, optional
     *  the strategy of storing customers credentials. Possible values: store, do_not_store, ask. Default value: store.
     *  Note: If the value is ask, on the Connect page customer will be able to choose whether to save or not his credentials on Salt Edge side
     *
     * @param $credentialsStrategy
     * @return $this
     */
    public function setCredentialsStrategy($credentialsStrategy): self
    {
        $this->options['credentials_strategy'] = $credentialsStrategy;
        return self;
    }

    /**
     * $return_error_class boolean, optional
     *  whether to append error_class to return_to URL. Defaults to false
     *
     * @param $returnErrorClass
     * @return $this
     */
    public function setReturnErrorClass($returnErrorClass): self
    {
        $this->options['return_error_class'] = $returnErrorClass;
        return self;
    }

    /**
     * $theme string, optional
     *  theme of Salt Edge Connect template. If not passed or available for the current template, will use default.
     *  Allowed values: default, dark.
     * @param $theme
     * @return $this
     */
    public function setTheme($theme): self
    {
        $this->options['theme'] = $theme;
        return self;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->options;
    }
}
