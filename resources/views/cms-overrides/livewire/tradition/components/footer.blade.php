@php
    $hasContactDetails = filled($general->phone) || filled($general->email) || filled($general->address);
    $hasQrcodes = filled($general->wechat_qrcode) || filled($general->wechat_official_qrcode);
@endphp

<footer id="nhgrc-footer" class="nhgrc-footer">
    <div class="nhgrc-footer__halo nhgrc-footer__halo--left" aria-hidden="true"></div>
    <div class="nhgrc-footer__halo nhgrc-footer__halo--right" aria-hidden="true"></div>

    <div class="nhgrc-footer__content container mx-auto">
        <section class="nhgrc-footer__brand" aria-label="平台信息">
            <a class="nhgrc-footer__logo-link" href="{{ \Wsmallnews\Cms\Support\Utils::route('index') }}" wire:navigate>
                <img class="nhgrc-footer__logo" src="{{ asset('image/logo.png') }}" alt="{{ $general->copyright ?: '国家棉花种质资源库' }}">
            </a>

            @if ($general->copyright)
                <p class="nhgrc-footer__intro">{{ $general->copyright }}</p>
            @endif

            <div class="nhgrc-footer__social" aria-label="关注我们">
                @if ($general->wechat_qrcode)
                    <a href="#nhgrc-footer-qrcodes" aria-label="{{ __('sn-cms::cms.frontend.personal_wechat') }}">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-right" aria-hidden="true" />
                    </a>
                @endif
                @if ($general->wechat_official_qrcode)
                    <a href="#nhgrc-footer-qrcodes" aria-label="{{ __('sn-cms::cms.frontend.official_account') }}">
                        <x-filament::icon icon="heroicon-o-qr-code" aria-hidden="true" />
                    </a>
                @endif
                @if ($general->email)
                    <a href="mailto:{{ $general->email }}" aria-label="{{ __('sn-cms::cms.frontend.contact_email') }}">
                        <x-filament::icon icon="heroicon-o-envelope" aria-hidden="true" />
                    </a>
                @endif
            </div>
        </section>

        <nav class="nhgrc-footer__navigation" aria-label="{{ __('sn-cms::cms.frontend.footer_nav') }}">
            @foreach ($navigations as $navigation)
                <section class="nhgrc-footer__nav-group">
                    <h2>
                        <a
                            @if ($navigation->children->isEmpty())
                                {{ \Filament\Support\generate_href_html($navigation->url_info['url'], $navigation->url_info['target'] ?? false) }}
                            @else
                                href="javascript:;"
                                role="presentation"
                            @endif
                        >
                            {{ $navigation->name_label }}
                        </a>
                    </h2>

                    @if ($navigation->children->isNotEmpty())
                        <ul role="list">
                            @foreach ($navigation->children->take(4) as $child)
                                <li>
                                    <a {{ \Filament\Support\generate_href_html($child->url_info['url'], $child->url_info['target'] ?? false) }}>
                                        {{ $child->name_label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            @endforeach
        </nav>

        <section class="nhgrc-footer__contact" aria-labelledby="nhgrc-footer-contact-title">
            <h2 id="nhgrc-footer-contact-title">{{ __('sn-cms::cms.frontend.follow_us') }}</h2>
            <p>{{ __('sn-cms::cms.frontend.follow_us_desc') }}</p>

            @if ($hasContactDetails)
                <address>
                    @if ($general->phone)
                        <a href="tel:{{ $general->phone }}">
                            <x-filament::icon icon="heroicon-o-phone" aria-hidden="true" />
                            <span>{{ $general->phone }}</span>
                        </a>
                    @endif
                    @if ($general->email)
                        <a href="mailto:{{ $general->email }}">
                            <x-filament::icon icon="heroicon-o-envelope" aria-hidden="true" />
                            <span>{{ $general->email }}</span>
                        </a>
                    @endif
                    @if ($general->address)
                        <div>
                            <x-filament::icon icon="heroicon-o-map-pin" aria-hidden="true" />
                            <span>{{ $general->address }}</span>
                        </div>
                    @endif
                </address>
            @endif

            @if ($hasQrcodes)
                <div id="nhgrc-footer-qrcodes" class="nhgrc-footer__qrcodes">
                    @if ($general->wechat_qrcode)
                        <figure>
                            <img src="{{ files_url($general->wechat_qrcode) }}" alt="{{ __('sn-cms::cms.frontend.wechat_qrcode') }}" loading="lazy">
                            <figcaption>{{ __('sn-cms::cms.frontend.personal_wechat') }}</figcaption>
                        </figure>
                    @endif
                    @if ($general->wechat_official_qrcode)
                        <figure>
                            <img src="{{ files_url($general->wechat_official_qrcode) }}" alt="{{ __('sn-cms::cms.frontend.official_qrcode') }}" loading="lazy">
                            <figcaption>{{ __('sn-cms::cms.frontend.official_account') }}</figcaption>
                        </figure>
                    @endif
                </div>
            @endif
        </section>
    </div>

    <div class="nhgrc-footer__bottom">
        <div class="container mx-auto">
            <p>{{ __('sn-cms::cms.frontend.copyright', ['copytime' => $general->copytime, 'copyright' => $general->copyright]) }}</p>

            @if ($general->beian_no)
                @if ($general->beian_url)
                    <a href="{{ $general->beian_url }}" target="_blank" rel="noopener">{{ $general->beian_no }}</a>
                @else
                    <span>{{ $general->beian_no }}</span>
                @endif
            @endif
        </div>
    </div>
</footer>
