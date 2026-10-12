<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('detailsApp.name', 'Sync-Trade') }} — Where Trading Intelligence Meets Technology</title>

  <!-- Favicons -->
  <link rel="icon" type="image/png" href="{{ asset('logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('logo/favicon/favicon.svg') }}" />
  <link rel="shortcut icon" href="{{ asset('logo/favicon/favicon.ico') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/favicon/apple-touch-icon.png') }}" />
  <link rel="manifest" href="{{ asset('logo/favicon/site.webmanifest') }}" />
  <style>
    :root {
      --gold: #f5c451;
      --gold2: #ffd97a;
      --gold-deep: #c9972a;
      --green: #22c55e;
      --green2: #4ade80;
      --green-deep: #15803d;
      --red: #ef4444;
      --red2: #f87171;
      --red-deep: #b91c1c;
      --radius: 18px;
      --maxw: 1240px;
    }

    [data-theme="dark"] {
      --bg: #0a0b0e;
      --bg2: #101217;
      --surface: rgba(24, 26, 33, 0.66);
      --surface-solid: #14161c;
      --line: rgba(245, 196, 81, 0.2);
      --line-soft: rgba(160, 160, 175, 0.1);
      --text: #e9ecf2;
      --muted: #97a0b3;
      --heading: #ffffff;
      --shadow: 0 26px 70px rgba(0, 0, 0, 0.6);
      --grad: radial-gradient(circle at 15% 8%, rgba(34, 197, 94, 0.1), transparent 42%), radial-gradient(circle at 85% 72%, rgba(239, 68, 68, 0.09), transparent 45%), radial-gradient(circle at 50% 120%, rgba(245, 196, 81, 0.08), transparent 55%), linear-gradient(180deg, #0a0b0e, #101217 55%, #0a0b0e);
    }

    [data-theme="light"] {
      --bg: #f6f4ef;
      --bg2: #efece4;
      --surface: rgba(255, 255, 255, 0.86);
      --surface-solid: #ffffff;
      --line: rgba(201, 151, 42, 0.32);
      --line-soft: rgba(90, 80, 60, 0.12);
      --text: #1d2129;
      --muted: #5d6472;
      --heading: #0e1116;
      --shadow: 0 18px 48px rgba(80, 70, 40, 0.14);
      --grad: radial-gradient(circle at 15% 8%, rgba(34, 197, 94, 0.1), transparent 42%), radial-gradient(circle at 85% 72%, rgba(239, 68, 68, 0.08), transparent 45%), linear-gradient(180deg, #f6f4ef, #efece4 55%, #f6f4ef);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
      overflow-x: hidden;
      width: 100%;
      max-width: 100%;
    }

    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.65;
      overflow-x: hidden;
      width: 100%;
      max-width: 100%;
      transition: background .4s, color .4s;
    }

    img,
    svg,
    canvas,
    video,
    table {
      max-width: 100%;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    .grad-bg {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background: var(--grad);
    }

    #hero-3d {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
    }

    .wrap {
      position: relative;
      z-index: 3;
      overflow-x: hidden;
      width: 100%;
      max-width: 100%;
    }

    .gold {
      background: linear-gradient(120deg, var(--gold2), var(--gold), var(--gold-deep));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .grn {
      background: linear-gradient(120deg, var(--green2), var(--green), var(--green-deep));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .rd {
      background: linear-gradient(120deg, var(--red2), var(--red), var(--red-deep));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    /* NAV */
    nav {
      position: sticky;
      top: 0;
      z-index: 60;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      padding: 11px 5vw;
      backdrop-filter: blur(18px);
      background: color-mix(in srgb, var(--bg) 72%, transparent);
      border-bottom: 1px solid var(--line);
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
    }

    .brand img {
      height: 66px;
      filter: drop-shadow(0 0 18px rgba(34, 197, 94, 0.45));
    }

    .nav-links {
      display: flex;
      gap: 22px;
      font-size: .9rem;
      color: var(--muted);
    }

    .nav-links a:hover {
      color: var(--gold);
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    #walletAddr {
      display: none;
      font-family: monospace;
      font-size: .78rem;
      color: var(--gold);
      padding: 8px 11px;
      border: 1px solid var(--line);
      border-radius: 11px;
      background: color-mix(in srgb, var(--gold) 10%, transparent);
    }

    .theme-toggle {
      cursor: pointer;
      width: 42px;
      height: 42px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: var(--surface);
      display: grid;
      place-items: center;
      font-size: 1.1rem;
      color: var(--text);
      transition: transform .2s, border-color .2s;
      flex-shrink: 0;
    }

    .theme-toggle:hover {
      transform: translateY(-2px);
      border-color: var(--gold);
    }

    .btn {
      cursor: pointer;
      border: none;
      font-weight: 700;
      letter-spacing: .3px;
      padding: 12px 24px;
      border-radius: 12px;
      font-size: .9rem;
      white-space: nowrap;
      flex-shrink: 0;
      background: linear-gradient(135deg, var(--gold2), var(--gold-deep));
      color: #1a1407;
      box-shadow: 0 0 30px rgba(245, 196, 81, 0.4);
      transition: transform .15s, box-shadow .2s;
      position: relative;
      overflow: hidden;
    }

    .btn::after {
      content: "";
      position: absolute;
      top: 0;
      left: -130%;
      width: 55%;
      height: 100%;
      background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.6), transparent);
      transform: skewX(-20deg);
      animation: shine 3.6s infinite;
    }

    @keyframes shine {
      0% {
        left: -130%
      }

      48%,
      100% {
        left: 140%
      }
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 52px rgba(245, 196, 81, 0.6);
    }

    .btn.grnbtn {
      background: linear-gradient(135deg, var(--green2), var(--green-deep));
      color: #04240f;
      box-shadow: 0 0 30px rgba(34, 197, 94, 0.4);
    }

    .btn.ghost {
      background: transparent;
      border: 1px solid var(--line);
      color: var(--gold);
      box-shadow: none;
    }

    .btn.ghost::after {
      display: none;
    }

    /* TICKER */
    .ticker-wrap {
      position: relative;
      z-index: 3;
      overflow: hidden;
      border-top: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
      background: color-mix(in srgb, var(--surface-solid) 68%, transparent);
      backdrop-filter: blur(10px);
    }

    /* HERO */
    header {
      min-height: auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 26px 5vw 34px;
      max-width: 1100px;
      margin: 0 auto;
      position: relative;
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: 9px;
      margin-bottom: 14px;
      padding: 7px 18px;
      border-radius: 100px;
      font-size: .72rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      border: 1px solid var(--line);
      background: var(--surface);
      color: var(--gold);
      animation: rise .5s ease forwards;
    }

    .pill i {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--green);
      box-shadow: 0 0 12px var(--green);
      animation: pulse 1.6s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
        transform: scale(1)
      }

      50% {
        opacity: .4;
        transform: scale(1.6)
      }
    }

    @keyframes rise {
      from {
        opacity: 0.2;
        transform: translateY(12px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    /* animated logo stage */
    .logo-stage {
      position: relative;
      width: min(340px, 86vw);
      height: 220px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      animation: rise .5s ease forwards;
    }

    .hero-logo {
      position: relative;
      z-index: 5;
      width: min(250px, 68vw);
      max-height: 120px;
      height: auto;
      object-fit: contain;
      filter: drop-shadow(0 4px 22px rgba(34, 197, 94, 0.35));
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0)
      }

      50% {
        transform: translateY(-8px)
      }
    }

    /* pulsing glow halo */
    .ls-glow {
      position: absolute;
      z-index: 1;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(34, 197, 94, 0.24) 0%, rgba(245, 196, 81, 0.1) 45%, transparent 72%);
      filter: blur(20px);
      pointer-events: none;
      animation: halo 4.5s ease-in-out infinite;
    }

    @keyframes halo {

      0%,
      100% {
        transform: scale(0.92);
        opacity: .75
      }

      50% {
        transform: scale(1.1);
        opacity: 1
      }
    }

    /* rotating rings */
    .ls-ring {
      position: absolute;
      border-radius: 50%;
      z-index: 2;
      pointer-events: none;
    }

    .ls-ring.r1 {
      width: 175px;
      height: 175px;
      border: 1px dashed rgba(245, 196, 81, 0.45);
      animation: spin 20s linear infinite;
    }

    .ls-ring.r2 {
      width: 215px;
      height: 215px;
      border: 1.5px solid transparent;
      border-top-color: rgba(34, 197, 94, 0.85);
      border-bottom-color: rgba(245, 196, 81, 0.6);
      animation: spin 14s linear infinite reverse;
    }

    .ls-ring.r3 {
      width: 250px;
      height: 250px;
      border: 1px solid rgba(34, 197, 94, 0.18);
      border-left: 1.5px solid rgba(245, 196, 81, 0.5);
      border-right: 1.5px solid rgba(34, 197, 94, 0.55);
      animation: spin 28s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg)
      }
    }

    /* orbiting dots */
    .ls-orbit {
      position: absolute;
      border-radius: 50%;
      z-index: 3;
      pointer-events: none;
    }

    .ls-orbit.o1 {
      width: 175px;
      height: 175px;
      animation: spin 20s linear infinite;
    }

    .ls-orbit.o2 {
      width: 215px;
      height: 215px;
      animation: spin 14s linear infinite reverse;
    }

    .ls-orbit i {
      position: absolute;
      top: -4px;
      left: 50%;
      width: 8px;
      height: 8px;
      margin-left: -4px;
      border-radius: 50%;
    }

    .ls-orbit.o1 i {
      background: var(--gold);
      box-shadow: 0 0 12px var(--gold);
    }

    .ls-orbit.o2 i {
      background: var(--green);
      box-shadow: 0 0 12px var(--green);
    }

    @media(max-width:560px) {
      .logo-stage {
        width: 250px;
        height: 190px;
        margin: 0 auto 10px;
      }

      .hero-logo {
        width: min(190px, 62vw);
        max-height: 90px;
      }

      .ls-glow {
        width: 160px;
        height: 160px;
      }

      .ls-ring.r1,
      .ls-orbit.o1 {
        width: 145px;
        height: 145px;
      }

      .ls-ring.r2,
      .ls-orbit.o2 {
        width: 175px;
        height: 175px;
      }

      .ls-ring.r3 {
        width: 205px;
        height: 205px;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .hero-logo,
      .ls-ring,
      .ls-orbit,
      .ls-glow,
      .pill,
      .pill i,
      h1,
      .hero-sub,
      .hero-cta,
      .stats {
        animation: none !important;
        opacity: 1 !important;
        transform: none !important;
      }
    }

    h1 {
      font-size: clamp(2rem, 5.2vw, 3.8rem);
      font-weight: 900;
      line-height: 1.08;
      letter-spacing: -1px;
      margin: 0 0 12px;
      max-width: 20ch;
      color: var(--heading);
      animation: rise .5s ease forwards;
      overflow-wrap: break-word;
    }

    .hero-sub {
      max-width: 680px;
      color: var(--muted);
      font-size: clamp(0.95rem, 1.8vw, 1.12rem);
      line-height: 1.55;
      margin: 0 0 20px;
      animation: rise .5s ease forwards;
    }

    .hero-cta {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 24px;
      animation: rise .5s ease forwards;
    }

    .stats {
      display: flex;
      gap: 20px 40px;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 4px;
      animation: rise .5s ease forwards;
    }

    .stat b {
      display: block;
      font-size: clamp(1.5rem, 4vw, 2.4rem);
      font-weight: 900;
    }

    .stat span {
      color: var(--muted);
      font-size: .76rem;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    /* SECTIONS */
    section {
      padding: 96px 6vw;
      max-width: var(--maxw);
      margin: 0 auto;
      width: 100%;
    }

    .reveal {
      opacity: 0;
      transform: translateY(40px);
      transition: opacity .8s, transform .8s;
    }

    .reveal.in {
      opacity: 1;
      transform: none;
    }

    .eyebrow {
      text-align: center;
      letter-spacing: 3px;
      text-transform: uppercase;
      font-size: .76rem;
      font-weight: 700;
      color: var(--gold);
    }

    h2 {
      text-align: center;
      font-size: clamp(1.9rem, 4.4vw, 3rem);
      font-weight: 900;
      margin: 12px 0 8px;
      color: var(--heading);
      letter-spacing: -.5px;
    }

    .lead {
      text-align: center;
      color: var(--muted);
      max-width: 760px;
      margin: 0 auto 50px;
    }

    .card {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      backdrop-filter: blur(12px);
      box-shadow: var(--shadow);
    }

    /* feature strip */
    .fstrip {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-top: 10px;
    }

    @media(max-width:860px) {
      .fstrip {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media(max-width:460px) {
      .fstrip {
        grid-template-columns: 1fr;
      }
    }

    .fs {
      padding: 22px;
      border-radius: 16px;
      background: var(--surface);
      border: 1px solid var(--line);
      backdrop-filter: blur(10px);
      text-align: center;
      transition: transform .3s, border-color .3s;
    }

    .fs:hover {
      transform: translateY(-6px);
      border-color: var(--gold);
    }

    .fs .e {
      font-size: 1.8rem;
    }

    .fs b {
      display: block;
      color: var(--heading);
      margin-top: 8px;
      font-size: .95rem;
    }

    .fs small {
      color: var(--muted);
      font-size: .78rem;
    }

    /* markets dash */
    .trade-dash {
      display: grid;
      grid-template-columns: 1.5fr 1fr;
      gap: 20px;
    }

    @media(max-width:860px) {
      .trade-dash {
        grid-template-columns: 1fr;
      }
    }

    .trade-dash>* {
      min-width: 0;
    }

    .chart-card {
      padding: 14px;
      border-radius: var(--radius);
      background: var(--surface);
      border: 1px solid var(--line);
      backdrop-filter: blur(12px);
      box-shadow: var(--shadow);
      min-width: 0;
      overflow: hidden;
    }

    .chart-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 6px 10px;
      flex-wrap: wrap;
      gap: 8px;
    }

    .chart-head .pair {
      font-weight: 800;
      color: var(--heading);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .chart-head .pair .dot {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      background: var(--green);
      box-shadow: 0 0 10px var(--green);
      animation: pulse 1.6s infinite;
    }

    /* about */
    .about {
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      gap: 40px;
      align-items: center;
    }

    .about>* {
      min-width: 0;
    }

    .about p {
      color: var(--muted);
      margin-bottom: 15px;
    }

    .about .hl {
      color: var(--gold);
      font-weight: 700;
    }

    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-top: 8px;
    }

    .ab {
      padding: 18px;
      border-radius: 14px;
      border: 1px solid var(--line);
      background: var(--surface);
      text-align: center;
      transition: transform .3s;
    }

    .ab:hover {
      transform: translateY(-5px);
    }

    .ab .e {
      font-size: 1.5rem;
    }

    .ab b {
      display: block;
      color: var(--heading);
      margin-top: 6px;
      font-size: .88rem;
    }

    .ab small {
      color: var(--muted);
      font-size: .72rem;
    }

    /* mv */
    .mv {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .mv>* {
      min-width: 0;
    }

    .mv .card {
      padding: 36px;
    }

    .mv h3 {
      font-size: 1.5rem;
      margin-bottom: 12px;
      color: var(--heading);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .mv .ico {
      width: 50px;
      height: 50px;
      border-radius: 14px;
      display: grid;
      place-items: center;
      font-size: 1.4rem;
      border: 1px solid var(--line);
    }

    .mv .ico.g {
      background: color-mix(in srgb, var(--gold) 14%, transparent);
    }

    .mv .ico.e {
      background: color-mix(in srgb, var(--green) 14%, transparent);
    }

    /* grid cards */
    .grid4 {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
    }

    @media(max-width:1000px) {
      .grid4 {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media(max-width:520px) {
      .grid4 {
        grid-template-columns: 1fr;
      }
    }

    .grid3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    @media(max-width:900px) {
      .grid3 {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media(max-width:560px) {
      .grid3 {
        grid-template-columns: 1fr;
      }
    }

    .fcard {
      padding: 26px;
      border-radius: var(--radius);
      background: var(--surface);
      border: 1px solid var(--line);
      backdrop-filter: blur(10px);
      transition: transform .3s, border-color .3s;
    }

    .fcard:hover {
      transform: translateY(-8px);
      border-color: var(--gold);
    }

    .fcard .ico {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      display: grid;
      place-items: center;
      font-size: 1.4rem;
      margin-bottom: 12px;
      border: 1px solid var(--line);
      background: color-mix(in srgb, var(--surface-solid) 50%, transparent);
    }

    .fcard h4 {
      color: var(--heading);
      margin-bottom: 6px;
    }

    .fcard p {
      color: var(--muted);
      font-size: .89rem;
    }

    .fcard .tag {
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: 1px;
      color: var(--gold);
    }

    /* packages */
    .pkgs {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    @media(max-width:860px) {
      .pkgs {
        grid-template-columns: 1fr;
      }
    }

    .pkg {
      position: relative;
      padding: 34px 28px;
      border-radius: 22px;
      background: var(--surface);
      border: 1px solid var(--line);
      backdrop-filter: blur(12px);
      box-shadow: var(--shadow);
      transition: transform .25s, box-shadow .3s;
      overflow: hidden;
    }

    .pkg:hover {
      transform: translateY(-8px);
      box-shadow: 0 0 46px rgba(245, 196, 81, 0.26);
    }

    .pkg.feat {
      border-color: var(--gold);
    }

    .pkg .ribbon {
      position: absolute;
      top: 16px;
      right: -34px;
      transform: rotate(45deg);
      background: linear-gradient(135deg, var(--gold2), var(--gold-deep));
      color: #1a1407;
      font-size: .66rem;
      font-weight: 800;
      letter-spacing: 1px;
      padding: 5px 42px;
    }

    .pkg h3 {
      color: var(--muted);
      letter-spacing: 1px;
      text-transform: uppercase;
      font-size: .82rem;
    }

    .pkg .rng {
      font-size: 2.3rem;
      font-weight: 900;
      margin: 8px 0 2px;
    }

    .pkg .roi {
      color: var(--heading);
      font-weight: 700;
      margin-top: 4px;
    }

    .pkg ul {
      list-style: none;
      margin: 20px 0;
      display: flex;
      flex-direction: column;
      gap: 11px;
    }

    .pkg li {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      color: var(--text);
      font-size: .9rem;
    }

    .pkg li::before {
      content: "✦";
      color: var(--green);
      margin-top: 1px;
    }

    .pkg .join {
      width: 100%;
      margin-top: 6px;
      justify-content: center;
      display: flex;
    }

    /* distribution donut */
    .dist {
      display: grid;
      grid-template-columns: .9fr 1.1fr;
      gap: 36px;
      align-items: center;
    }

    .dist>* {
      min-width: 0;
    }

    @media(max-width:760px) {
      .dist {
        grid-template-columns: 1fr;
      }
    }

    .dist-legend {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .dl {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 13px 16px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: var(--surface);
    }

    .dl .l {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .dl .sw {
      width: 14px;
      height: 14px;
      border-radius: 4px;
      flex-shrink: 0;
    }

    .dl b {
      font-weight: 800;
    }

    /* tables */
    .tbl {
      overflow-x: auto;
      border-radius: var(--radius);
      border: 1px solid var(--line);
      background: var(--surface);
      backdrop-filter: blur(10px);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 420px;
    }

    th,
    td {
      padding: 13px 18px;
      text-align: left;
      border-bottom: 1px solid var(--line-soft);
    }

    th {
      color: var(--gold);
      font-size: .76rem;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    td {
      color: var(--text);
      font-size: .9rem;
    }

    tr:last-child td {
      border-bottom: none;
    }

    td .hi {
      color: var(--gold);
      font-weight: 800;
    }

    td .hg {
      color: var(--green);
      font-weight: 800;
    }

    .bar {
      height: 8px;
      border-radius: 5px;
      background: linear-gradient(90deg, var(--green), var(--gold));
    }

    .two-col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 26px;
      align-items: start;
    }

    .two-col>* {
      min-width: 0;
    }

    @media(max-width:860px) {

      .two-col,
      .about,
      .mv,
      .dist {
        grid-template-columns: 1fr;
      }
    }

    /* ranks */
    .ranks {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
    }

    @media(max-width:900px) {
      .ranks {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media(max-width:480px) {
      .ranks {
        grid-template-columns: 1fr;
      }
    }

    .rank {
      padding: 28px 22px;
      border-radius: var(--radius);
      text-align: center;
      border: 1px solid var(--line);
      background: var(--surface);
      backdrop-filter: blur(10px);
      transition: transform .3s, box-shadow .3s;
    }

    .rank:hover {
      transform: translateY(-8px);
      box-shadow: 0 0 42px rgba(245, 196, 81, 0.22);
    }

    .rank .medal {
      font-size: 2.3rem;
    }

    .rank h4 {
      margin: 8px 0 2px;
      color: var(--heading);
      letter-spacing: 1px;
    }

    .rank .self {
      font-size: 1.6rem;
      font-weight: 900;
      margin: 6px 0;
    }

    .rank .q {
      color: var(--muted);
      font-size: .82rem;
      min-height: 38px;
    }

    .rank .di {
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px solid var(--line-soft);
      color: var(--gold);
      font-weight: 800;
      font-size: 1.05rem;
    }

    .rank .di small {
      display: block;
      color: var(--muted);
      font-weight: 500;
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* airdrop */
    .airdrop {
      border-radius: 24px;
      padding: 44px;
      position: relative;
      overflow: hidden;
      border: 1px solid var(--green);
      background: linear-gradient(135deg, color-mix(in srgb, var(--green) 16%, transparent), color-mix(in srgb, var(--gold) 8%, transparent));
      box-shadow: 0 0 56px rgba(34, 197, 94, 0.2);
    }

    .airdrop .rows {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-top: 22px;
    }

    @media(max-width:720px) {
      .airdrop .rows {
        grid-template-columns: 1fr;
      }
    }

    .airdrop .ar {
      background: color-mix(in srgb, var(--surface-solid) 45%, transparent);
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 20px;
    }

    .airdrop .ar b {
      color: var(--green2);
      font-size: 1.3rem;
      display: block;
    }

    /* features list */
    .feat-list {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }

    @media(max-width:860px) {
      .feat-list {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media(max-width:520px) {
      .feat-list {
        grid-template-columns: 1fr;
      }
    }

    .fl {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      padding: 16px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: var(--surface);
      backdrop-filter: blur(8px);
    }

    .fl .e {
      font-size: 1.2rem;
      flex-shrink: 0;
    }

    .fl b {
      color: var(--heading);
      display: block;
      font-size: .9rem;
    }

    .fl small {
      color: var(--muted);
      font-size: .8rem;
    }

    /* CTA */
    .cta-band {
      max-width: var(--maxw);
      margin: 40px auto;
      padding: 56px 6vw;
      border-radius: 26px;
      text-align: center;
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, color-mix(in srgb, var(--gold) 20%, transparent), color-mix(in srgb, var(--green) 10%, transparent));
      border: 1px solid var(--gold);
      box-shadow: var(--shadow);
    }

    .cta-band p {
      color: var(--muted);
      max-width: 600px;
      margin: 10px auto 26px;
    }

    /* risk note */
    .risk {
      max-width: var(--maxw);
      margin: 0 auto;
      padding: 0 6vw 10px;
    }

    .risk .box {
      border: 1px solid var(--red);
      border-left-width: 4px;
      border-radius: 14px;
      padding: 20px 24px;
      background: color-mix(in srgb, var(--red) 7%, transparent);
      color: var(--muted);
      font-size: .86rem;
    }

    .risk .box b {
      color: var(--red2);
    }

    /* footer */
    footer {
      border-top: 1px solid var(--line);
      margin-top: 50px;
      padding: 56px 6vw 40px;
      text-align: center;
      color: var(--muted);
    }

    .fbrand img {
      height: 84px;
      margin-bottom: 12px;
      filter: drop-shadow(0 0 18px rgba(34, 197, 94, 0.4));
    }

    .site-url {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 11px 24px;
      border-radius: 100px;
      border: 1px solid var(--gold);
      background: var(--surface);
      color: var(--gold);
      font-weight: 700;
      margin: 12px 0;
    }

    .fnav {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
      margin: 18px 0;
      font-size: .88rem;
    }

    .fnav a:hover {
      color: var(--gold);
    }

    .disclaimer {
      font-size: .72rem;
      color: var(--muted);
      opacity: .75;
      max-width: 760px;
      margin: 20px auto 0;
      line-height: 1.6;
    }

    /* modal */
    .modal-overlay {
      position: fixed;
      inset: 0;
      z-index: 300;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background: rgba(6, 6, 8, 0.76);
      backdrop-filter: blur(7px);
    }

    .modal-overlay.open {
      display: flex;
    }

    .modal {
      width: 100%;
      max-width: 420px;
      background: var(--surface-solid);
      border: 1px solid var(--line);
      border-radius: 22px;
      box-shadow: var(--shadow);
      padding: 34px 30px;
      position: relative;
      animation: popin .3s ease;
    }

    @keyframes popin {
      from {
        opacity: 0;
        transform: translateY(20px) scale(.97)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    .modal .close {
      position: absolute;
      top: 16px;
      right: 18px;
      cursor: pointer;
      font-size: 1.3rem;
      color: var(--muted);
      background: none;
      border: none;
    }

    .modal .close:hover {
      color: var(--gold);
    }

    .modal .mlogo {
      display: flex;
      justify-content: center;
      margin-bottom: 10px;
    }

    .modal .mlogo img {
      height: 40px;
    }

    .modal h3 {
      text-align: center;
      color: var(--heading);
      font-size: 1.5rem;
      margin: 6px 0 2px;
    }

    .modal .msub {
      text-align: center;
      color: var(--muted);
      font-size: .86rem;
      margin-bottom: 22px;
    }

    .field {
      margin-bottom: 14px;
    }

    .field label {
      display: block;
      font-size: .78rem;
      color: var(--muted);
      margin-bottom: 6px;
    }

    .field input {
      width: 100%;
      padding: 13px 15px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: color-mix(in srgb, var(--surface) 70%, transparent);
      color: var(--text);
      font-size: .92rem;
      outline: none;
      transition: border-color .2s;
    }

    .field input:focus {
      border-color: var(--gold);
    }

    .modal .btn {
      width: 100%;
      margin-top: 6px;
      justify-content: center;
      display: flex;
    }

    .modal .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 18px 0;
      color: var(--muted);
      font-size: .76rem;
    }

    .modal .divider::before,
    .modal .divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: var(--line);
    }

    .modal .alt {
      text-align: center;
      color: var(--muted);
      font-size: .85rem;
      margin-top: 16px;
    }

    .modal .alt a {
      color: var(--gold);
      font-weight: 700;
      cursor: pointer;
    }

    /* ===== branded loader ===== */
    #loader {
      position: fixed;
      inset: 0;
      z-index: 600;
      display: grid;
      place-items: center;
      background:
        radial-gradient(circle at 50% 42%, rgba(34, 197, 94, 0.1), transparent 55%), var(--bg);
      transition: opacity .6s ease, visibility .6s;
    }

    #loader.hide {
      opacity: 0;
      visibility: hidden;
    }

    .ld-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 26px;
      text-align: center;
    }

    .ld-stage {
      position: relative;
      width: 200px;
      height: 200px;
      display: grid;
      place-items: center;
    }

    .ld-stage img {
      width: 138px;
      z-index: 4;
      filter: drop-shadow(0 8px 26px rgba(34, 197, 94, 0.5));
      animation: ldpulse 2s ease-in-out infinite;
    }

    @keyframes ldpulse {

      0%,
      100% {
        transform: scale(0.96)
      }

      50% {
        transform: scale(1.04)
      }
    }

    .ld-ring {
      position: absolute;
      border-radius: 50%;
    }

    .ld-ring.a {
      width: 200px;
      height: 200px;
      border: 2px solid transparent;
      border-top-color: var(--gold);
      border-right-color: rgba(245, 196, 81, 0.3);
      animation: spin 1.1s linear infinite;
    }

    .ld-ring.b {
      width: 166px;
      height: 166px;
      border: 2px solid transparent;
      border-bottom-color: var(--green);
      border-left-color: rgba(34, 197, 94, 0.3);
      animation: spin 1.5s linear infinite reverse;
    }

    .ld-ring.c {
      width: 132px;
      height: 132px;
      border: 2px solid transparent;
      border-top-color: var(--red);
      animation: spin 1.9s linear infinite;
    }

    .ld-bar {
      width: 200px;
      max-width: 70vw;
      height: 4px;
      border-radius: 4px;
      background: color-mix(in srgb, var(--muted) 24%, transparent);
      overflow: hidden;
    }

    .ld-bar i {
      display: block;
      height: 100%;
      width: 40%;
      border-radius: 4px;
      background: linear-gradient(90deg, var(--green), var(--gold), var(--red));
      animation: ldbar 1.3s ease-in-out infinite;
    }

    @keyframes ldbar {
      0% {
        margin-left: -42%
      }

      100% {
        margin-left: 102%
      }
    }

    .ld-txt {
      color: var(--muted);
      letter-spacing: 4px;
      text-transform: uppercase;
      font-size: .72rem;
    }

    #toast {
      position: fixed;
      bottom: 26px;
      left: 50%;
      transform: translateX(-50%) translateY(140px);
      z-index: 400;
      background: var(--surface-solid);
      border: 1px solid var(--gold);
      border-radius: 14px;
      padding: 14px 22px;
      color: var(--text);
      box-shadow: var(--shadow);
      transition: transform .35s cubic-bezier(.2, .9, .3, 1.2);
      max-width: 90vw;
      font-size: .9rem;
    }

    #toast.show {
      transform: translateX(-50%) translateY(0);
    }

    @media(prefers-reduced-motion:reduce) {
      * {
        animation: none !important;
        transition: none !important;
      }
    }

    @media(max-width:980px) {
      .nav-links {
        display: none;
      }
    }

    @media(max-width:640px) {
      header {
        padding: 20px 4vw 26px;
      }

      section {
        padding: 66px 5vw;
      }

      h1 {
        max-width: 100%;
      }

      .cta-band {
        padding: 40px 6vw;
        border-radius: 20px;
      }
    }

    @media(max-width:560px) {
      nav {
        padding: 10px 4vw;
        gap: 8px;
      }

      .brand img {
        height: 48px;
      }

      .nav-right {
        gap: 7px;
      }

      .nav-right .btn {
        padding: 9px 14px;
        font-size: .8rem;
      }

      .theme-toggle {
        width: 38px;
        height: 38px;
      }
    }

    @media(max-width:380px) {
      .nav-right .btn.ghost {
        display: none;
      }
    }
  </style>
</head>

<body>
  <div id="loader">
    <div class="ld-wrap">
      <div class="ld-stage">
        <span class="ld-ring a"></span><span class="ld-ring b"></span><span class="ld-ring c"></span>
        <img src="{{ asset('fs_assets/logo.png') }}" alt="{{ config('detailsApp.name', 'Sync-Trade') }}" />
      </div>
      <div class="ld-bar"><i></i></div>
      <div class="ld-txt">Loading Markets…</div>
    </div>
  </div>
  <div class="grad-bg"></div>
  <canvas id="hero-3d"></canvas>

  <div class="wrap">
    <!-- NAV -->
    <nav>
      <a href="{{ url('/') }}" class="brand"><img src="{{ asset('fs_assets/logo.png') }}" alt="{{ config('detailsApp.name', 'Sync-Trade') }}" /></a>
      <div class="nav-links">
        <a href="#about">About</a><a href="#what">What We Do</a>
        <a href="#packages">Packages</a><a href="#income">Income</a><a href="#ranks">Partnership</a><a href="#airdrop">Airdrop</a>
      </div>
      <div class="nav-right">
        <button class="theme-toggle" id="themeToggle" title="Toggle light/dark">🌙</button>
        <span id="walletAddr"></span>
        @if (session()->has('MEMBER_ID'))
        <a href="{{ url('member/dashboard') }}" class="btn">📊 Dashboard</a>
        @else
        <button class="btn" id="connectBtn" onclick="connectWallet()">🔗 Connect Wallet</button>
        @endif
      </div>
    </nav>

    <!-- TICKER -->
    <div class="ticker-wrap">
      <div id="tvTicker" style="width:100%"></div>
    </div>

    <!-- HERO -->
    <header id="home">
      <div class="logo-stage">
        <span class="ls-glow"></span>
        <span class="ls-ring r1"></span>
        <span class="ls-ring r2"></span>
        <span class="ls-ring r3"></span>
        <span class="ls-orbit o1"><i></i></span>
        <span class="ls-orbit o2"><i></i></span>
        <img src="{{ asset('fs_assets/logo.png') }}" class="hero-logo" alt="{{ config('detailsApp.name', 'Sync-Trade') }}" />
      </div>
      <span class="pill"><i></i> Algorithmic · Copy Trading · Market Analysis</span>
      <h1>Where <span class="grn">Trading Intelligence</span> Meets <span class="gold">Technology</span></h1>
      <p class="hero-sub">A technology-driven forex trading &amp; investment platform combining algorithmic systems, copy trading and structured market analysis — built on transparency, discipline and responsible risk management.</p>
      <div class="hero-cta">
        @if (session()->has('MEMBER_ID'))
        <a href="{{ url('member/dashboard') }}" class="btn">📊 Dashboard</a>
        @else
        <button class="btn" onclick="connectWallet()">🔗 Connect Wallet</button>
        @endif
        <a href="#packages" class="btn grnbtn">View Packages</a>
        <a href="#about" class="btn ghost">Learn More</a>
      </div>
      <div class="stats">
        <div class="stat"><b class="gold">Up to 10%</b><span>Monthly Profit</span></div>
        <div class="stat"><b class="grn">$50</b><span>Min Investment</span></div>
        <div class="stat"><b class="rd">10</b><span>Team Levels</span></div>
        <div class="stat"><b class="gold">24×5</b><span>Markets Open</span></div>
      </div>
    </header>

    <!-- FEATURE STRIP -->
    <section class="reveal" style="padding-top:40px">
      <div class="fstrip">
        <div class="fs">
          <div class="e">🤖</div><b>Algorithmic Trading</b><small>Automated bots &amp; signals</small>
        </div>
        <div class="fs">
          <div class="e">👥</div><b>Copy Trading</b><small>Follow expert strategies</small>
        </div>
        <div class="fs">
          <div class="e">📊</div><b>Market Analysis</b><small>Data-driven decisions</small>
        </div>
        <div class="fs">
          <div class="e">🛡️</div><b>Risk Management</b><small>Disciplined oversight</small>
        </div>
      </div>
    </section>


    <!-- ABOUT -->
    <section id="about" class="reveal">
      <div class="eyebrow">Know Our Company</div>
      <h2>About <span class="gold">Sync-Trade</span></h2>
      <p class="lead">A forex trading and investment company focused on leveraging advanced trading technology, algorithmic systems and copy-trading strategies to identify opportunities in the global currency markets.</p>
      <div class="about">
        <div>
          <p>By combining <span class="hl">data-driven market analysis, automated trading bots and experienced trading strategies</span>, Sync-Trade creates a structured and technology-driven approach to forex trading.</p>
          <p>Our mission is to make modern trading strategies more accessible to investors through transparent operations, disciplined risk management, and a commitment to responsible investment practices.</p>
          <p>We believe successful participation in financial markets demands research, discipline, continuous improvement and responsible decision-making — not technology alone.</p>
          <div class="about-grid">
            <div class="ab">
              <div class="e">🤖</div><b>Technology-Driven</b><small>Automation &amp; data</small>
            </div>
            <div class="ab">
              <div class="e">⚙️</div><b>Systematic</b><small>Rules over emotion</small>
            </div>
            <div class="ab">
              <div class="e">🔍</div><b>Transparent</b><small>Clear reporting</small>
            </div>
            <div class="ab">
              <div class="e">🤝</div><b>Investor-Focused</b><small>Accountability first</small>
            </div>
          </div>
        </div>
        <div class="card" style="padding:14px;overflow:hidden">
          <svg viewBox="0 0 520 360" style="width:100%;height:auto;display:block;border-radius:12px" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="ag" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#15803d" stop-opacity=".3" />
                <stop offset="1" stop-color="#c9972a" stop-opacity=".12" />
              </linearGradient>
              <linearGradient id="af" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#22c55e" stop-opacity=".45" />
                <stop offset="1" stop-color="#22c55e" stop-opacity="0" />
              </linearGradient>
            </defs>
            <rect width="520" height="360" fill="url(#ag)" rx="12" />
            <g stroke="#f5c451" stroke-opacity=".28" fill="none">
              <circle cx="390" cy="150" r="92" />
              <ellipse cx="390" cy="150" rx="92" ry="34" />
              <ellipse cx="390" cy="150" rx="58" ry="92" />
              <line x1="298" y1="150" x2="482" y2="150" />
            </g>
            <g id="aboutCandles"></g>
            <path id="aboutArea" fill="url(#af)" />
            <path id="aboutLine" fill="none" stroke="#f5c451" stroke-width="3" stroke-linecap="round" />
            <g font-family="Segoe UI" font-weight="900" font-size="28" fill="#4ade80"><text x="30" y="300">€</text><text x="76" y="320">$</text><text x="34" y="60">£</text></g>
          </svg>
        </div>
      </div>
    </section>

    <!-- MISSION / VISION -->
    <section id="mv" class="reveal">
      <div class="eyebrow">Our Purpose</div>
      <h2>Mission &amp; <span class="grn">Vision</span></h2>
      <div class="mv">
        <div class="card">
          <h3><span class="ico g">🎯</span> Our Mission</h3>
          <p style="color:var(--muted)">To connect investment opportunities with advanced trading technology — using algorithmic trading, copy trading and systematic market analysis to pursue sustainable performance while prioritizing transparency and investor protection.</p>
        </div>
        <div class="card">
          <h3><span class="ico e">👁️</span> Our Vision</h3>
          <p style="color:var(--muted)">To become a trusted name in technology-driven forex trading by combining innovation, transparency and responsible investment management to create long-term value for our investors.</p>
        </div>
      </div>
    </section>

    <!-- WHAT WE DO -->
    <section id="what" class="reveal">
      <div class="eyebrow">Our Capabilities</div>
      <h2>What We <span class="gold">Do</span></h2>
      <p class="lead">Five disciplines working together — from signal to settlement.</p>
      <div class="grid3">
        <div class="fcard">
          <div class="ico">💱</div>
          <h4>Forex Trading</h4>
          <p>We analyze global currency markets to identify opportunities based on price movements, trends and economic developments.</p>
        </div>
        <div class="fcard">
          <div class="ico">👥</div>
          <h4>Copy Trading</h4>
          <p>We replicate selected trading strategies and trader expertise according to predefined investment parameters.</p>
        </div>
        <div class="fcard">
          <div class="ico">🤖</div>
          <h4>Algorithmic Trading</h4>
          <p>Automated systems and bots execute trades according to predefined rules, market signals and strategic parameters.</p>
        </div>
        <div class="fcard">
          <div class="ico">🛡️</div>
          <h4>Risk Management</h4>
          <p>Position sizing, exposure monitoring, trading limits and systematic oversight help manage volatility and losses.</p>
        </div>
        <div class="fcard">
          <div class="ico">📈</div>
          <h4>Investment Management</h4>
          <p>A structured approach to participating in forex trading, supported by clear communication and reporting.</p>
        </div>
        <div class="fcard">
          <div class="ico">🔔</div>
          <h4>Why Sync-Trade</h4>
          <p>Technology-driven, systematic, transparent and risk-aware — built around an investor-focused approach.</p>
        </div>
      </div>
    </section>

    <!-- WHAT IS FOREX -->
    <section id="forex" class="reveal">
      <div class="eyebrow">Learn The Market</div>
      <h2>What is <span class="grn">Forex</span>?</h2>
      <p class="lead">Forex (Foreign Exchange) is the world's largest financial market, where currencies are bought and sold to profit from changes in their exchange rates — operating 24 hours a day, five days a week, connecting global economies, banks, businesses and individual traders.</p>
      <div class="grid3">
        <div class="fcard">
          <div class="tag">01</div>
          <h4>Scalping</h4>
          <p>Multiple quick trades to profit from small price moves within seconds or minutes.</p>
        </div>
        <div class="fcard">
          <div class="tag">02</div>
          <h4>Day Trading</h4>
          <p>Opening and closing trades within the same day to capture short-term moves.</p>
        </div>
        <div class="fcard">
          <div class="tag">03</div>
          <h4>Swing Trading</h4>
          <p>Holding trades for days or weeks to benefit from market swings.</p>
        </div>
        <div class="fcard">
          <div class="tag">04</div>
          <h4>Position Trading</h4>
          <p>Holding for weeks or months based on major market trends.</p>
        </div>
        <div class="fcard">
          <div class="tag">05</div>
          <h4>News Trading</h4>
          <p>Trading the impact of economic announcements, rates and global events.</p>
        </div>
        <div class="fcard">
          <div class="tag">06</div>
          <h4>Trend &amp; Breakout</h4>
          <p>Following direction, or entering as price breaks key support/resistance.</p>
        </div>
      </div>
    </section>

    <!-- PACKAGES -->
    <section id="packages" class="reveal">
      <div class="eyebrow">Choose Your Plan</div>
      <h2>Investment <span class="gold">Packages</span></h2>
      <p class="lead">Each package has its own monthly trading-profit target. Profit is paid daily, up to that month's maximum. Minimum deposit $50 · settlement in USDT.</p>
      <div class="pkgs">
        <div class="pkg">
          <h3>Starter</h3>
          <div class="rng grn">$50 – $500</div>
          <div class="roi">Up to <span class="gold">5%</span> monthly profit</div>
          <ul>
            <li>Daily profit payouts</li>
            <li>Referral &amp; team income</li>
            <li>Copy &amp; algo trading access</li>
            <li>90-day lock-in on trading wallet</li>
          </ul>
          <button class="btn grnbtn join" data-pkg="Starter ($50–$500)">Invest Now</button>
        </div>
        <div class="pkg feat">
          <div class="ribbon">POPULAR</div>
          <h3>Growth</h3>
          <div class="rng gold">$600 – $5,000</div>
          <div class="roi">Up to <span class="gold">7%</span> monthly profit</div>
          <ul>
            <li>Higher monthly profit target</li>
            <li>Full 10-level team income</li>
            <li>Partnership program eligible</li>
            <li>Priority analysis &amp; signals</li>
          </ul>
          <button class="btn join" data-pkg="Growth ($600–$5,000)">Invest Now</button>
        </div>
        <div class="pkg">
          <h3>Elite</h3>
          <div class="rng rd">$6,000+</div>
          <div class="roi">Up to <span class="gold">10%</span> monthly profit</div>
          <ul>
            <li>Highest monthly profit target</li>
            <li>No upper limit — "No-Limit"</li>
            <li>Diamond partnership eligible</li>
            <li>Full income &amp; rewards suite</li>
          </ul>
          <button class="btn grnbtn join" data-pkg="Elite ($6,000+)">Invest Now</button>
        </div>
      </div>
    </section>

    <!-- PACKAGE DISTRIBUTION -->
    <section id="dist" class="reveal">
      <div class="eyebrow">Transparent Allocation</div>
      <h2>Package <span class="grn">Distribution</span></h2>
      <p class="lead">Every investment is allocated across clear, fixed channels — 70% goes straight into the trading wallet where your profit is calculated.</p>
      <div class="dist">
        <div style="display:grid;place-items:center">
          <svg viewBox="0 0 220 220" style="width:min(300px,80vw);height:auto" id="donut" xmlns="http://www.w3.org/2000/svg"></svg>
        </div>
        <div class="dist-legend" id="distLegend"></div>
      </div>
    </section>

    <!-- INCOME STREAMS -->
    <section id="income" class="reveal">
      <div class="eyebrow">Multiple Ways To Earn</div>
      <h2>Income <span class="gold">Streams</span></h2>
      <p class="lead">Earn from your own trading and your network — monthly trading profit, referrals, team profit, team investment share and Hero of the Month.</p>
      <div class="two-col">
        <div>
          <h3 style="color:var(--heading);margin-bottom:6px">Monthly Trading Profit</h3>
          <p style="color:var(--muted);font-size:.9rem;margin-bottom:14px">Paid daily, up to each month's target.</p>
          <div class="tbl">
            <table>
              <thead>
                <tr>
                  <th>Package</th>
                  <th>Monthly Target</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>$50 – $500</td>
                  <td><span class="hg">Up to 5%</span></td>
                </tr>
                <tr>
                  <td>$600 – $5,000</td>
                  <td><span class="hg">Up to 7%</span></td>
                </tr>
                <tr>
                  <td>$6,000 and above</td>
                  <td><span class="hg">Up to 10%</span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <h3 style="color:var(--heading);margin:26px 0 6px">Referral Bonus</h3>
          <div class="tbl">
            <table>
              <thead>
                <tr>
                  <th>Level</th>
                  <th>Bonus</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Level 1</td>
                  <td><span class="hi">5%</span></td>
                </tr>
                <tr>
                  <td>Level 2</td>
                  <td><span class="hi">3%</span></td>
                </tr>
                <tr>
                  <td>Level 3</td>
                  <td><span class="hi">2%</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div>
          <h3 style="color:var(--heading);margin-bottom:6px">Team Trading Profit · 10 Levels</h3>
          <p style="color:var(--muted);font-size:.9rem;margin-bottom:14px">Calculated on daily trading profit across your team.</p>
          <div class="tbl">
            <table>
              <thead>
                <tr>
                  <th>Level</th>
                  <th>Share</th>
                  <th style="width:34%">&nbsp;</th>
                </tr>
              </thead>
              <tbody id="teamRows"></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="grid4" style="margin-top:28px">
        <div class="fcard">
          <div class="ico">📅</div>
          <h4>Daily Team Investment Share</h4>
          <p>Earn 1% per level across 10 levels — unlocked as you add direct referrals.</p>
        </div>
        <div class="fcard">
          <div class="ico">👑</div>
          <h4>Hero of the Month</h4>
          <p>2% of all investments pooled and paid on the 1st — to the top direct-referral investor.</p>
        </div>
        <div class="fcard">
          <div class="ico">⚡</div>
          <h4>Instant Withdrawal</h4>
          <p>Income wallet withdrawable anytime. Min withdrawal 10 USDT.</p>
        </div>
        <div class="fcard">
          <div class="ico">🔓</div>
          <h4>Dynamic Lock-In</h4>
          <p>Trading wallet unlocks after the lock-in period or on reaching a set return target.</p>
        </div>
      </div>
    </section>

    <!-- PARTNERSHIP / RANKS -->
    <section id="ranks" class="reveal">
      <div class="eyebrow">Higher Tiers · Greater Rewards</div>
      <h2>Partnership <span class="gold">Program</span></h2>
      <p class="lead">Earn a daily percentage of your total team business, up to each tier's cap. Only the higher package applies.</p>
      <div class="ranks">
        <div class="rank">
          <div class="medal">🥈</div>
          <h4>SILVER</h4>
          <div class="self grn">$1,000</div>
          <div class="q">2% on daily total team business, up to 200% ($2,000)</div>
          <div class="di">2% Daily<small>Team Business</small></div>
        </div>
        <div class="rank">
          <div class="medal">🥇</div>
          <h4>GOLD</h4>
          <div class="self gold">$5,000</div>
          <div class="q">4% on daily total team business, up to 400% ($20,000)</div>
          <div class="di">4% Daily<small>Team Business</small></div>
        </div>
        <div class="rank">
          <div class="medal">🏆</div>
          <h4>PLATINUM</h4>
          <div class="self rd">$10,000</div>
          <div class="q">6% on daily total team business, up to 600% ($60,000)</div>
          <div class="di">6% Daily<small>Team Business</small></div>
        </div>
        <div class="rank">
          <div class="medal">💎</div>
          <h4>DIAMOND</h4>
          <div class="self grn">$25,000</div>
          <div class="q">8% on daily total team business, up to 800% ($200,000)</div>
          <div class="di">8% Daily<small>Team Business</small></div>
        </div>
      </div>
    </section>

    <!-- AIRDROP -->
    <section id="airdrop" class="reveal">
      <div class="eyebrow">Promotion</div>
      <h2>Pepe Token <span class="grn">Airdrop</span></h2>
      <p class="lead">Earn Pepe BEP-20 tokens for engagement and growing the Sync-Trade community.</p>
      <div class="airdrop">
        <h3 style="color:var(--heading);font-size:1.4rem">🪂 Earn Pepe (BEP-20) Tokens</h3>
        <div class="rows">
          <div class="ar"><b>500 Tokens</b><small style="color:var(--muted)">Per message — send 1 message to earn, max 10 messages daily.</small></div>
          <div class="ar"><b>500 Tokens</b><small style="color:var(--muted)">Per direct registration (inactive referral).</small></div>
          <div class="ar"><b>500 Tokens</b><small style="color:var(--muted)">Per direct activation.</small></div>
        </div>
      </div>
    </section>

    <!-- PLATFORM FEATURES -->
    <section id="features" class="reveal">
      <div class="eyebrow">Built For Traders</div>
      <h2>Platform <span class="gold">Features</span></h2>
      <p class="lead">Everything you need to invest, track and grow — in one modern dashboard.</p>
      <div class="feat-list">
        <div class="fl"><span class="e">📈</span>
          <div><b>Live Forex Charts</b><small>Real-time charts on your dashboard.</small></div>
        </div>
        <div class="fl"><span class="e">🖥️</span>
          <div><b>Live MT5 Account View</b><small>Transparent trading account visibility.</small></div>
        </div>
        <div class="fl"><span class="e">⏱️</span>
          <div><b>Instant Withdrawals</b><small>Queued automatically if funds are pending.</small></div>
        </div>
        <div class="fl"><span class="e">🔁</span>
          <div><b>P2P USDT Exchange</b><small>Easy on/off-ramp for USDT.</small></div>
        </div>
        <div class="fl"><span class="e">💬</span>
          <div><b>Live Chat Support</b><small>Help whenever you need it.</small></div>
        </div>
        <div class="fl"><span class="e">🌍</span>
          <div><b>Multi-Language</b><small>Full translation support.</small></div>
        </div>
        <div class="fl"><span class="e">🔔</span>
          <div><b>News &amp; Notifications</b><small>Live scroller and alerts.</small></div>
        </div>
        <div class="fl"><span class="e">📄</span>
          <div><b>Reports &amp; Statements</b><small>Clear, downloadable reporting.</small></div>
        </div>
        <div class="fl"><span class="e">🔗</span>
          <div><b>Wallet &amp; DApp Access</b><small>Connect wallet or DApp browser.</small></div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <div class="cta-band reveal">
      <div class="eyebrow">Join Sync-Trade</div>
      <h2>Start Your <span class="gold">Trading Journey</span> Today</h2>
      <p>Join a community built on transparency, discipline and technology. Create your account and put modern trading strategies to work.</p>
      <div class="hero-cta" style="opacity:1;animation:none">
        @if (session()->has('MEMBER_ID'))
        <a href="{{ url('member/dashboard') }}" class="btn">📊 Dashboard</a>
        @else
        <button class="btn" onclick="connectWallet()">🔗 Connect Wallet</button>
        @endif
        <a href="#packages" class="btn grnbtn">View Packages</a>
      </div>
    </div>

    <!-- RISK NOTE -->

    <!-- FOOTER -->
    <footer>
      <div class="fbrand"><img src="{{ asset('fs_assets/logo.png') }}" alt="{{ config('detailsApp.name', 'Sync-Trade') }}" /></div>
      <div class="fnav">
        <a href="#about">About</a><a href="#what">What We Do</a><a href="#forex">Forex</a>
        <a href="#packages">Packages</a><a href="#income">Income</a><a href="#ranks">Partnership</a><a href="#airdrop">Airdrop</a><a href="#features">Features</a>
        @if (session()->has('MEMBER_ID'))
        <a href="{{ url('member/dashboard') }}">Dashboard</a>
        @endif
      </div>
      <div class="site-url">🌐 www.sync-trade.com</div>
      <p style="font-size:.84rem">Sync-Trade — Where Trading Intelligence Meets Technology.</p>
      <p class="disclaimer">Sync-Trade is a technology-driven forex trading and investment platform. Trading in forex and digital assets carries significant risk and may not be suitable for all investors. Returns are targets, not guarantees; past or projected performance does not ensure future results. Please invest responsibly.</p>
      <p style="margin-top:14px;font-size:.76rem">© 2026 Sync-Trade · All rights reserved.</p>
    </footer>
  </div>

  <div id="toast"></div>

  <script>
    /* LOADER */
    window.addEventListener('load', () => setTimeout(() => {
      const l = document.getElementById('loader');
      if (l) l.classList.add('hide');
    }, 700));
    setTimeout(() => {
      const l = document.getElementById('loader');
      if (l) l.classList.add('hide');
    }, 3500);

    /* THEME */
    const root = document.documentElement,
      tbtn = document.getElementById('themeToggle');

    function applyTheme(t) {
      root.setAttribute('data-theme', t);
      tbtn.textContent = t === 'dark' ? '🌙' : '☀️';
      try {
        localStorage.setItem('st-theme', t);
      } catch (e) {}
    }
    (function() {
      let s = 'dark';
      try {
        s = localStorage.getItem('st-theme') || 'dark';
      } catch (e) {}
      applyTheme(s);
    })();
    tbtn.addEventListener('click', () => applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'));

    /* TOAST */
    function toast(m) {
      const t = document.getElementById('toast');
      t.textContent = m;
      t.classList.add('show');
      clearTimeout(t._h);
      t._h = setTimeout(() => t.classList.remove('show'), 4200);
    }

    /* WALLET CONNECT (BNB Smart Chain / BEP-20) & AUTHENTICATION */
    const BSC = {
      chainId: '0x38',
      chainName: 'BNB Smart Chain',
      nativeCurrency: {
        name: 'BNB',
        symbol: 'BNB',
        decimals: 18
      },
      rpcUrls: ['https://bsc-dataseed.binance.org/'],
      blockExplorerUrls: ['https://bscscan.com/']
    };
    let account = null;
    const short = a => a.slice(0, 6) + '…' + a.slice(-4);
    const isMemberAuthenticated = @json(session()->has('MEMBER_ID'));
    const memberDashboardUrl = @json(url('member/dashboard'));

    let eip6963Providers = [];
    window.addEventListener('eip6963:announceProvider', function(e) {
      if (e && e.detail && !eip6963Providers.some(p => p.info.uuid === e.detail.info.uuid)) {
        eip6963Providers.push(e.detail);
      }
    });
    window.dispatchEvent(new Event('eip6963:requestProvider'));

    function getProvider() {
      if (window.ethereum) {
        if (window.ethereum.providers && window.ethereum.providers.length) {
          return window.ethereum.providers[0];
        }
        return window.ethereum;
      }
      if (window.trustwallet) return window.trustwallet;
      if (window.mathwallet) return window.mathwallet;
      if (eip6963Providers.length > 0) return eip6963Providers[0].provider;
      return null;
    }

    function setConnected(a) {
      account = a;
      const p = document.getElementById('walletAddr');
      if (p) {
        p.style.display = 'inline-block';
        p.textContent = short(a);
      }
    }

    async function validateAndRedirect(address) {
      toast('Connected: ' + short(address) + '. Validating member status…');
      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

      try {
        const response = await fetch("{{ route('addressValidate') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken
          },
          body: new URLSearchParams({
            "address": address,
            "_token": csrfToken
          })
        });

        const data = await response.json();
        console.log("addressValidate response:", data);

        if (data.code === 1) {
          toast('Member verified! Redirecting to Member Dashboard…');
          setTimeout(() => {
            window.location.href = "{{ url('member/dashboard') }}";
          }, 500);
        } else {
          toast('New wallet detected. Redirecting to Member Registration…');
          setTimeout(() => {
            window.location.href = "{{ url('/member/register/1') }}";
          }, 500);
        }
      } catch (err) {
        console.error("Address validation error:", err);
        toast('Validation error. Please try again.');
      }
    }

    async function connectWallet() {
      if (isMemberAuthenticated) {
        window.location.href = memberDashboardUrl;
        return;
      }

      const provider = getProvider();
      if (!provider) {
        toast('No Web3 wallet found. Please install MetaMask, Trust Wallet, or open in a Web3 DApp browser.');
        window.open('https://metamask.io/download/', '_blank');
        return;
      }

      try {
        const accs = await provider.request({
          method: 'eth_requestAccounts'
        });
        if (!accs || !accs[0]) {
          toast('No account selected.');
          return;
        }

        try {
          await provider.request({
            method: 'wallet_switchEthereumChain',
            params: [{
              chainId: BSC.chainId
            }]
          });
        } catch (sw) {
          if (sw.code === 4902) {
            await provider.request({
              method: 'wallet_addEthereumChain',
              params: [BSC]
            });
          }
        }

        setConnected(accs[0]);
        await validateAndRedirect(accs[0]);
      } catch (e) {
        console.error(e);
        toast(e.code === 4001 ? 'Connection request rejected.' : 'Wallet connection failed.');
      }
    }

    if (window.ethereum) {
      ethereum.on && ethereum.on('accountsChanged', accs => {
        if (accs && accs.length) {
          setConnected(accs[0]);
          validateAndRedirect(accs[0]);
        } else {
          location.reload();
        }
      });
    }

    document.querySelectorAll('.join').forEach(b => b.addEventListener('click', () => {
      if (isMemberAuthenticated) {
        window.location.href = memberDashboardUrl;
        return;
      }
      if (!account) {
        toast('Connect your wallet to invest in ' + b.dataset.pkg);
        connectWallet();
        return;
      }
      validateAndRedirect(account);
    }));

    /* TEAM TRADING PROFIT TABLE */
    (function() {
      const T = [
        ['1', '5%', 100],
        ['2', '5%', 100],
        ['3', '4%', 80],
        ['4', '4%', 80],
        ['5', '3%', 60],
        ['6', '3%', 60],
        ['7', '2%', 40],
        ['8', '2%', 40],
        ['9', '1%', 20],
        ['10', '1%', 20]
      ];
      document.getElementById('teamRows').innerHTML = T.map(([l, p, w]) => `<tr><td>Level ${l}</td><td><span class="hi">${p}</span></td><td><div class="bar" style="width:${w}%"></div></td></tr>`).join('');
    })();

    /* DISTRIBUTION DONUT */
    (function() {
      const data = [{
          label: 'Trading Wallet',
          val: 70,
          color: '#22c55e'
        },
        {
          label: 'Referral Bonus',
          val: 10,
          color: '#f5c451'
        },
        {
          label: 'Team Performance Bonus',
          val: 10,
          color: '#ef4444'
        },
        {
          label: 'Team Trading Profit',
          val: 8,
          color: '#4ade80'
        },
        {
          label: 'Hero of the Month',
          val: 2,
          color: '#c9972a'
        }
      ];
      const svg = document.getElementById('donut');
      const cx = 110,
        cy = 110,
        r = 80,
        rr = 48;
      let a0 = -Math.PI / 2;
      const NS = 'http://www.w3.org/2000/svg';
      data.forEach(d => {
        const ang = d.val / 100 * Math.PI * 2,
          a1 = a0 + ang;
        const x0 = cx + r * Math.cos(a0),
          y0 = cy + r * Math.sin(a0),
          x1 = cx + r * Math.cos(a1),
          y1 = cy + r * Math.sin(a1);
        const xi1 = cx + rr * Math.cos(a1),
          yi1 = cy + rr * Math.sin(a1),
          xi0 = cx + rr * Math.cos(a0),
          yi0 = cy + rr * Math.sin(a0);
        const large = ang > Math.PI ? 1 : 0;
        const p = document.createElementNS(NS, 'path');
        p.setAttribute('d', `M${x0},${y0} A${r},${r} 0 ${large} 1 ${x1},${y1} L${xi1},${yi1} A${rr},${rr} 0 ${large} 0 ${xi0},${yi0} Z`);
        p.setAttribute('fill', d.color);
        p.setAttribute('opacity', '0.9');
        svg.appendChild(p);
        a0 = a1;
      });
      const t1 = document.createElementNS(NS, 'text');
      t1.setAttribute('x', cx);
      t1.setAttribute('y', cy - 4);
      t1.setAttribute('text-anchor', 'middle');
      t1.setAttribute('font-size', '22');
      t1.setAttribute('font-weight', '900');
      t1.setAttribute('fill', '#22c55e');
      t1.textContent = '70%';
      svg.appendChild(t1);
      const t2 = document.createElementNS(NS, 'text');
      t2.setAttribute('x', cx);
      t2.setAttribute('y', cy + 16);
      t2.setAttribute('text-anchor', 'middle');
      t2.setAttribute('font-size', '10');
      t2.setAttribute('fill', '#97a0b3');
      t2.textContent = 'TRADING WALLET';
      svg.appendChild(t2);
      document.getElementById('distLegend').innerHTML = data.map(d => `<div class="dl"><div class="l"><span class="sw" style="background:${d.color}"></span>${d.label}</div><b style="color:${d.color}">${d.val}%</b></div>`).join('');
    })();

    /* ABOUT BANNER CANDLES */
    (function() {
      const g = document.getElementById('aboutCandles');
      if (!g) return;
      const line = document.getElementById('aboutLine'),
        area = document.getElementById('aboutArea');
      const n = 20,
        x0 = 30,
        x1 = 250,
        base = 200;
      let data = [];

      function build() {
        data = [];
        let v = 0;
        for (let i = 0; i < n; i++) {
          v += (Math.random() - .42) * 14;
          data.push(v);
        }
      }
      build();

      function render() {
        const step = (x1 - x0) / (n - 1);
        let c = '',
          lp = [];
        data.forEach((v, i) => {
          const x = x0 + i * step,
            o = base - v,
            cc = base - ((data[i + 1] !== undefined ? data[i + 1] : v)) - Math.random() * 5,
            up = cc <= o,
            col = up ? '#22c55e' : '#ef4444',
            top = Math.min(o, cc) - 5,
            bot = Math.max(o, cc) + 5;
          c += `<line x1="${x}" y1="${top-6}" x2="${x}" y2="${bot+6}" stroke="${col}" stroke-width="1.4" opacity=".55"/><rect x="${x-4}" y="${top}" width="8" height="${Math.max(3,bot-top)}" fill="${col}" opacity=".7"/>`;
          lp.push(`${x},${base-v}`);
        });
        g.innerHTML = c;
        line.setAttribute('d', 'M' + lp.join(' L'));
        area.setAttribute('d', `M${x0},360 L` + lp.join(' L') + ` L${x1},360 Z`);
      }
      render();
      setInterval(() => {
        data.shift();
        data.push(data[data.length - 1] + (Math.random() - .42) * 14);
        render();
      }, 1800);
    })();

    /* REVEAL */
    const io = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('in');
        io.unobserve(e.target);
      }
    }), {
      threshold: 0.1
    });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));

    /* TRADINGVIEW */
    function widgetTV(id, src, cfg) {
      const host = document.getElementById(id);
      if (!host) return;
      host.innerHTML = '';
      const wc = document.createElement('div');
      wc.className = 'tradingview-widget-container';
      wc.style.height = '100%';
      wc.style.width = '100%';
      const inner = document.createElement('div');
      inner.className = 'tradingview-widget-container__widget';
      inner.style.height = '100%';
      inner.style.width = '100%';
      wc.appendChild(inner);
      const s = document.createElement('script');
      s.async = true;
      s.src = src;
      s.innerHTML = JSON.stringify(cfg);
      wc.appendChild(s);
      host.appendChild(wc);
    }

    function buildTV() {
      const theme = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      widgetTV('tvTicker', 'https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js', {
        symbols: [{
          proName: 'FX:EURUSD',
          title: 'EUR/USD'
        }, {
          proName: 'FX:GBPUSD',
          title: 'GBP/USD'
        }, {
          proName: 'FX:USDJPY',
          title: 'USD/JPY'
        }, {
          proName: 'OANDA:XAUUSD',
          title: 'Gold'
        }, {
          proName: 'BINANCE:BTCUSDT',
          title: 'BTC/USDT'
        }, {
          proName: 'BINANCE:ETHUSDT',
          title: 'ETH/USDT'
        }, {
          proName: 'BINANCE:BNBUSDT',
          title: 'BNB/USDT'
        }],
        showSymbolLogo: true,
        isTransparent: true,
        displayMode: 'adaptive',
        colorTheme: theme,
        locale: 'en'
      });
    }
    buildTV();
    tbtn.addEventListener('click', () => setTimeout(buildTV, 80));

    /* ===== MARKET-FLOW BACKGROUND (Canvas 2D, unique) ===== */
    (function() {
      const cv = document.getElementById('hero-3d');
      if (!cv) return;
      const ctx = cv.getContext('2d');
      let w, h, dpr, cols, waves, t = 0,
        scrollY = 0;
      const GOLD = '245,196,81',
        GREEN = '34,197,94',
        RED = '239,68,68';

      function resize() {
        dpr = Math.min(devicePixelRatio, 2);
        w = cv.width = innerWidth * dpr;
        h = cv.height = innerHeight * dpr;
        cv.style.width = innerWidth + 'px';
        cv.style.height = innerHeight + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        build();
      }

      function build() {
        const W = innerWidth,
          H = innerHeight;
        // rising candle columns
        const n = Math.max(16, Math.floor(W / 70));
        cols = [];
        for (let i = 0; i < n; i++) {
          const up = Math.random() > 0.5;
          cols.push({
            x: (i + 0.5) * (W / n) + (Math.random() - 0.5) * 18,
            bh: Math.random() * 90 + 40,
            wick: Math.random() * 40 + 20,
            bw: Math.random() * 10 + 9,
            up,
            speed: Math.random() * 0.25 + 0.12,
            phase: Math.random() * Math.PI * 2,
            alpha: Math.random() * 0.1 + 0.05
          });
        }
        // flowing price "tide" layers
        waves = [{
            color: GOLD,
            amp: 70,
            freq: 0.0011,
            speed: -0.1,
            base: 0.5,
            op: 0.55,
            lw: 2.6,
            fill: 0.1
          },
          {
            color: GREEN,
            amp: 52,
            freq: 0.0015,
            speed: 0.16,
            base: 0.6,
            op: 0.6,
            lw: 2.2,
            fill: 0.12
          },
          {
            color: RED,
            amp: 38,
            freq: 0.0021,
            speed: 0.26,
            base: 0.7,
            op: 0.4,
            lw: 1.8,
            fill: 0.07
          }
        ];
      }
      addEventListener('resize', resize);
      addEventListener('scroll', () => {
        scrollY = window.scrollY || 0;
      }, {
        passive: true
      });

      function draw() {
        const W = innerWidth,
          H = innerHeight;
        ctx.clearRect(0, 0, W, H);
        t += 1;
        const py = scrollY * 0.15;
        // subtle moving grid
        ctx.lineWidth = 1;
        ctx.strokeStyle = 'rgba(245,196,81,0.04)';
        const gs = 64,
          off = (t * 0.2) % gs;
        for (let x = -off; x < W; x += gs) {
          ctx.beginPath();
          ctx.moveTo(x, 0);
          ctx.lineTo(x, H);
          ctx.stroke();
        }
        for (let y = -off; y < H; y += gs) {
          ctx.beginPath();
          ctx.moveTo(0, y);
          ctx.lineTo(W, y);
          ctx.stroke();
        }
        // rising candle columns (bottom)
        cols.forEach(c => {
          const sway = Math.sin(t * 0.01 * c.speed + c.phase) * 6;
          const cx = c.x + sway;
          const baseY = H - 10 + py * 0.3;
          const bh = c.bh + Math.sin(t * 0.02 * c.speed + c.phase) * 10;
          const col = c.up ? GREEN : RED;
          ctx.strokeStyle = `rgba(${col},${c.alpha+0.04})`;
          ctx.lineWidth = 1.4;
          ctx.beginPath();
          ctx.moveTo(cx, baseY - bh - c.wick);
          ctx.lineTo(cx, baseY + 6);
          ctx.stroke();
          ctx.fillStyle = `rgba(${col},${c.alpha})`;
          ctx.fillRect(cx - c.bw / 2, baseY - bh, c.bw, bh);
        });
        // flowing price "tide" layers (gradient fill + glowing stroke)
        const yAt = (wv, x) => H * wv.base + py * 0.4 + Math.sin(x * wv.freq + t * 0.01 * wv.speed * 10) * wv.amp + Math.sin(x * wv.freq * 2.3 + t * 0.006) * wv.amp * 0.35 + Math.sin(x * wv.freq * 0.6 - t * 0.004) * wv.amp * 0.25;
        waves.forEach(wv => {
          // area fill
          ctx.beginPath();
          ctx.moveTo(0, H);
          for (let x = 0; x <= W; x += 8) {
            const y = yAt(wv, x);
            ctx.lineTo(x, y);
          }
          ctx.lineTo(W, H);
          ctx.closePath();
          const g = ctx.createLinearGradient(0, H * wv.base - wv.amp, 0, H);
          g.addColorStop(0, `rgba(${wv.color},${wv.fill})`);
          g.addColorStop(1, `rgba(${wv.color},0)`);
          ctx.fillStyle = g;
          ctx.fill();
          // glowing stroke
          ctx.beginPath();
          for (let x = 0; x <= W; x += 8) {
            const y = yAt(wv, x);
            x ? ctx.lineTo(x, y) : ctx.moveTo(x, y);
          }
          ctx.strokeStyle = `rgba(${wv.color},${wv.op})`;
          ctx.lineWidth = wv.lw;
          ctx.lineJoin = 'round';
          ctx.shadowColor = `rgba(${wv.color},0.6)`;
          ctx.shadowBlur = 12;
          ctx.stroke();
          ctx.shadowBlur = 0;
          // glowing dot riding the wave
          const dx = (t * 1.4) % W,
            dy = yAt(wv, dx);
          ctx.beginPath();
          ctx.arc(dx, dy, 4, 0, 7);
          ctx.fillStyle = `rgba(${wv.color},1)`;
          ctx.shadowColor = `rgba(${wv.color},0.9)`;
          ctx.shadowBlur = 16;
          ctx.fill();
          ctx.shadowBlur = 0;
        });
        requestAnimationFrame(draw);
      }
      resize();
      draw();
    })();
  </script>
</body>

</html>