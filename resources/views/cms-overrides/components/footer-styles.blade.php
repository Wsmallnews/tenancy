<style>
    .nhgrc-footer {
        --footer-ink: #14233f;
        --footer-muted: #68758a;
        --footer-line: #dce5f0;
        position: relative;
        isolation: isolate;
        overflow: hidden;
        margin-top: 48px;
        background: #f8fbff;
        color: var(--footer-muted);
    }

    .nhgrc-footer__content {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(210px, 1fr) minmax(500px, 2.7fr) minmax(220px, 0.95fr);
        gap: clamp(32px, 4vw, 64px);
        max-width: 1440px;
        padding: 38px 32px 32px;
    }

    .nhgrc-footer__brand,
    .nhgrc-footer__contact,
    .nhgrc-footer__nav-group {
        min-width: 0;
    }

    .nhgrc-footer__logo-link {
        display: inline-flex;
        max-width: 220px;
        border-radius: 4px;
    }

    .nhgrc-footer__logo {
        display: block;
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    .nhgrc-footer__intro {
        max-width: 280px;
        margin-top: 14px;
        font-size: 12px;
        line-height: 1.7;
    }

    .nhgrc-footer__social {
        display: flex;
        min-height: 34px;
        gap: 8px;
        margin-top: 14px;
    }

    .nhgrc-footer__social:empty {
        display: none;
    }

    .nhgrc-footer__social a {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border: 1px solid #d6e1ee;
        border-radius: 50%;
        background: #fff;
        color: #4774aa;
        transition: border-color 160ms ease, background-color 160ms ease, color 160ms ease;
    }

    .nhgrc-footer__social a:hover {
        border-color: #2d67a7;
        background: #2d67a7;
        color: #fff;
    }

    .nhgrc-footer__social svg,
    .nhgrc-footer__contact svg {
        width: 17px;
        height: 17px;
        flex: none;
    }

    .nhgrc-footer__navigation {
        display: grid;
        grid-template-columns: repeat(4, minmax(92px, 1fr));
        align-content: start;
        gap: 20px 34px;
    }

    .nhgrc-footer__nav-group h2 a {
        text-decoration: none;
    }

    .nhgrc-footer__nav-group h2,
    .nhgrc-footer__contact h2 {
        color: var(--footer-ink);
        font-size: 15px;
        font-weight: 700;
        line-height: 1.5;
    }

    .nhgrc-footer__contact h2::after {
        display: block;
        width: 24px;
        height: 2px;
        margin-top: 8px;
        background: #4f84bf;
        content: "";
    }

    .nhgrc-footer__nav-group ul {
        display: grid;
        gap: 7px;
        margin-top: 10px;
        font-size: 12px;
        line-height: 1.6;
    }

    .nhgrc-footer a {
        outline: none;
        transition: color 160ms ease;
    }

    .nhgrc-footer a:hover {
        color: #245f9e;
    }

    .nhgrc-footer a:focus-visible {
        border-radius: 3px;
        outline: 2px solid #2d67a7;
        outline-offset: 3px;
    }

    .nhgrc-footer__contact {
        border-left: 1px solid var(--footer-line);
        padding-left: 30px;
    }

    .nhgrc-footer__contact > p {
        margin-top: 10px;
        font-size: 12px;
        line-height: 1.65;
    }

    .nhgrc-footer__contact address {
        display: grid;
        gap: 9px;
        margin-top: 12px;
        font-size: 12px;
        font-style: normal;
        line-height: 1.65;
    }

    .nhgrc-footer__contact address a,
    .nhgrc-footer__contact address div {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .nhgrc-footer__contact address svg {
        margin-top: 2px;
        color: #4f84bf;
    }

    .nhgrc-footer__qrcodes {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 12px;
        margin-top: 12px;
    }

    .nhgrc-footer__qrcodes figure {
        display: grid;
        justify-items: center;
        gap: 6px;
    }

    .nhgrc-footer__qrcodes img {
        width: 72px;
        height: 72px;
        border: 5px solid #fff;
        box-shadow: 0 0 0 1px #dbe5ef;
        object-fit: cover;
    }

    .nhgrc-footer__qrcodes figcaption {
        font-size: 11px;
        color: #8793a5;
    }

    .nhgrc-footer__bottom {
        position: relative;
        z-index: 1;
        min-height: 52px;
        background: #16365d;
        color: #c8d5e4;
        font-size: 12px;
    }

    .nhgrc-footer__bottom > div {
        display: flex;
        min-height: 52px;
        max-width: 1440px;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding-inline: 32px;
    }

    .nhgrc-footer__halo {
        position: absolute;
        z-index: 0;
        pointer-events: none;
    }

    .nhgrc-footer__halo--left {
        top: -92px;
        left: -76px;
        width: 250px;
        height: 250px;
        border: 42px solid rgb(74 134 196 / 8%);
        border-radius: 50%;
    }

    .nhgrc-footer__halo--right {
        right: 4%;
        bottom: 92px;
        width: 88px;
        height: 88px;
        opacity: 0.36;
        background-image: radial-gradient(circle, #6a9bc9 1.5px, transparent 1.6px);
        background-size: 11px 11px;
    }

    @media (max-width: 1180px) {
        .nhgrc-footer__content {
            grid-template-columns: minmax(200px, 0.85fr) minmax(480px, 2.15fr);
            gap: 46px;
        }

        .nhgrc-footer__contact {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 200px minmax(0, 1fr);
            gap: 10px 46px;
            border-top: 1px solid var(--footer-line);
            border-left: 0;
            padding-top: 30px;
            padding-left: 0;
        }

        .nhgrc-footer__contact > p,
        .nhgrc-footer__contact > address,
        .nhgrc-footer__qrcodes {
            grid-column: 2;
            margin-top: 0;
        }
    }

    @media (max-width: 760px) {
        .nhgrc-footer {
            margin-top: 48px;
        }

        .nhgrc-footer__content {
            grid-template-columns: 1fr;
            gap: 36px;
            padding: 44px 20px 38px;
        }

        .nhgrc-footer__brand {
            text-align: center;
        }

        .nhgrc-footer__logo-link {
            max-width: 260px;
        }

        .nhgrc-footer__intro {
            margin-inline: auto;
        }

        .nhgrc-footer__social,
        .nhgrc-footer__qrcodes {
            justify-content: center;
        }

        .nhgrc-footer__navigation {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 30px 26px;
            border-top: 1px solid var(--footer-line);
            padding-top: 34px;
        }

        .nhgrc-footer__contact {
            grid-column: auto;
            display: block;
            padding-top: 34px;
            text-align: center;
        }

        .nhgrc-footer__contact h2::after {
            margin-inline: auto;
        }

        .nhgrc-footer__contact address a,
        .nhgrc-footer__contact address div {
            justify-content: center;
        }

        .nhgrc-footer__bottom > div {
            flex-direction: column;
            justify-content: center;
            gap: 6px;
            padding: 18px 20px;
            text-align: center;
        }
    }

    .dark .nhgrc-footer {
        --footer-ink: #eef5ff;
        --footer-muted: #a9b7ca;
        --footer-line: #334864;
        background: #101e31;
    }

    .dark .nhgrc-footer__social a {
        border-color: #3b526f;
        background: #172a43;
        color: #a8c8eb;
    }
</style>
