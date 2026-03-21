<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — Hawks Credits</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #060D1F;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            width: 55%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        /* SVG cityscape background */
        .cityscape {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dark gradient overlay over cityscape */
        .left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(6,13,31,0.3) 0%,
                rgba(6,13,31,0.5) 50%,
                rgba(6,13,31,0.92) 100%
            );
            z-index: 1;
        }

        .left-content {
            position: relative;
            z-index: 2;
            padding: 3rem;
        }

        .left-tagline {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.15;
            margin-bottom: 1rem;
            text-shadow: 0 2px 20px rgba(0,0,0,0.5);
        }

        .left-tagline span {
            color: #D4A017;
        }

        .left-sub {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
            color: rgba(148,163,184,0.8);
            letter-spacing: 0.05em;
            margin-bottom: 2.5rem;
        }

        /* Stats row */
        .stats-row {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            border-left: 2px solid #D4A017;
            padding-left: 1rem;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
            display: block;
        }

        .stat-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: rgba(148,163,184,0.7);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* Feature pills */
        .features {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .feature-pill {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            color: rgba(212,160,23,0.9);
            border: 1px solid rgba(212,160,23,0.3);
            background: rgba(212,160,23,0.08);
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            letter-spacing: 0.05em;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
            background: #0B1428;
            border-left: 1px solid rgba(30,58,138,0.3);
            position: relative;
            overflow: hidden;
        }

        /* Subtle glow behind form */
        .right-panel::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(29,78,216,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .right-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(212,160,23,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Logo */
        .logo-area {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1D4ED8, #1E3A8A);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(29,78,216,0.3), 0 0 0 1px rgba(212,160,23,0.2);
            flex-shrink: 0;
        }

        .logo-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .logo-text p {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: rgba(100,116,139,1);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* Heading */
        .form-heading {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.4rem;
        }

        .form-sub {
            font-size: 0.85rem;
            color: rgba(100,116,139,1);
            margin-bottom: 2rem;
        }

        /* Form elements */
        .field {
            margin-bottom: 1.2rem;
        }

        .field label {
            display: block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(148,163,184,0.8);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 0.5rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(100,116,139,0.8);
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            background: rgba(15,23,42,0.8);
            border: 1px solid rgba(30,41,59,1);
            border-radius: 10px;
            color: #e2e8f0;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s;
        }

        .input-wrap input:focus {
            border-color: rgba(29,78,216,0.6);
            box-shadow: 0 0 0 3px rgba(29,78,216,0.12);
            background: rgba(15,23,42,1);
        }

        .input-wrap input::placeholder {
            color: rgba(71,85,105,0.8);
        }

        .pass-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(100,116,139,0.7);
            padding: 4px;
            transition: color 0.2s;
        }

        .pass-toggle:hover { color: rgba(148,163,184,1); }

        /* Remember + forgot */
        .row-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 15px;
            height: 15px;
            border-radius: 4px;
            border: 1px solid rgba(51,65,85,1);
            background: rgba(15,23,42,0.8);
            accent-color: #1D4ED8;
            cursor: pointer;
        }

        .remember span {
            font-size: 0.82rem;
            color: rgba(100,116,139,1);
        }

        .forgot {
            font-size: 0.8rem;
            color: #D4A017;
            text-decoration: none;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            transition: color 0.2s;
        }
        .forgot:hover { color: #F59E0B; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #D4A017, #F59E0B);
            border: none;
            border-radius: 10px;
            color: #060D1F;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 20px rgba(212,160,23,0.25);
            letter-spacing: 0.01em;
            margin-bottom: 1.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 25px rgba(212,160,23,0.35);
        }

        .btn-submit:active { transform: scale(0.98); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        /* Spinner */
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(6,13,31,0.3);
            border-top-color: #060D1F;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.2rem;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: rgba(30,41,59,0.8);
        }
        .divider span {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: rgba(71,85,105,0.8);
            letter-spacing: 0.1em;
        }

        /* Register link */
        .register-link {
            text-align: center;
            font-size: 0.83rem;
            color: rgba(100,116,139,1);
        }
        .register-link a {
            color: #D4A017;
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
            transition: color 0.2s;
        }
        .register-link a:hover { color: #F59E0B; }

        /* Error alert */
        .error-alert {
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 10px;
            padding: 0.8rem 1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            color: rgba(252,165,165,0.9);
            font-size: 0.83rem;
        }

        /* Footer */
        .form-footer {
            margin-top: 2rem;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            color: rgba(51,65,85,0.8);
            letter-spacing: 0.06em;
        }

        /* Animated dots on cityscape */
        .window-lights {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
        }

        .window-light {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(255,220,100,0.6);
            border-radius: 50%;
            animation: blink var(--dur, 3s) ease-in-out infinite var(--delay, 0s);
        }

        @keyframes blink {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.9; }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 2rem; }
        }
    </style>
</head>
<body x-data="{ loading: false, showPass: false }">

    {{-- ══ LEFT PANEL — Bank Cityscape ══ --}}
    <div class="left-panel">

        {{-- SVG Cityscape --}}
        <svg class="cityscape" viewBox="0 0 900 700" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <defs>
                <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#060D1F"/>
                    <stop offset="40%" stop-color="#0B1428"/>
                    <stop offset="100%" stop-color="#0F2040"/>
                </linearGradient>
                <linearGradient id="bldg1" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#1a2a4a"/>
                    <stop offset="100%" stop-color="#0d1929"/>
                </linearGradient>
                <linearGradient id="bldg2" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#152238"/>
                    <stop offset="100%" stop-color="#0a1520"/>
                </linearGradient>
                <linearGradient id="bldg3" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#1e3358"/>
                    <stop offset="100%" stop-color="#0e1f36"/>
                </linearGradient>
                <linearGradient id="gold_glow" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#D4A017" stop-opacity="0.8"/>
                    <stop offset="100%" stop-color="#D4A017" stop-opacity="0"/>
                </linearGradient>
                <filter id="glow">
                    <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                    <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
                </filter>
                <filter id="softglow">
                    <feGaussianBlur stdDeviation="6" result="coloredBlur"/>
                    <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
                </filter>
            </defs>

            {{-- Sky --}}
            <rect width="900" height="700" fill="url(#sky)"/>

            {{-- Stars --}}
            <g fill="white" opacity="0.5">
                <circle cx="50" cy="40" r="1"/><circle cx="120" cy="20" r="0.8"/>
                <circle cx="200" cy="55" r="1.2"/><circle cx="300" cy="15" r="0.9"/>
                <circle cx="420" cy="35" r="1"/><circle cx="550" cy="10" r="0.7"/>
                <circle cx="650" cy="45" r="1.1"/><circle cx="750" cy="25" r="0.8"/>
                <circle cx="820" cy="60" r="1"/><circle cx="880" cy="30" r="0.9"/>
                <circle cx="160" cy="80" r="0.7"/><circle cx="350" cy="70" r="1"/>
                <circle cx="480" cy="90" r="0.8"/><circle cx="700" cy="75" r="1.2"/>
            </g>

            {{-- Moon --}}
            <circle cx="780" cy="80" r="35" fill="#1a2d50" filter="url(#softglow)"/>
            <circle cx="780" cy="80" r="32" fill="#243d6a"/>
            <circle cx="780" cy="80" r="30" fill="none" stroke="#D4A017" stroke-width="0.5" opacity="0.4"/>
            <circle cx="790" cy="75" r="18" fill="#1a2d50"/>

            {{-- Distant city glow --}}
            <ellipse cx="450" cy="480" rx="500" ry="80" fill="#1D4ED8" opacity="0.06"/>
            <ellipse cx="450" cy="500" rx="400" ry="60" fill="#D4A017" opacity="0.04"/>

            {{-- Background buildings (far) --}}
            <rect x="0" y="320" width="60" height="380" fill="url(#bldg2)" opacity="0.5"/>
            <rect x="55" y="350" width="40" height="350" fill="url(#bldg2)" opacity="0.5"/>
            <rect x="820" y="310" width="80" height="390" fill="url(#bldg2)" opacity="0.5"/>
            <rect x="840" y="380" width="60" height="320" fill="url(#bldg2)" opacity="0.4"/>

            {{-- Mid buildings --}}
            <rect x="30" y="270" width="80" height="430" fill="url(#bldg1)" opacity="0.7"/>
            <rect x="100" y="300" width="55" height="400" fill="url(#bldg1)" opacity="0.7"/>
            <rect x="750" y="280" width="75" height="420" fill="url(#bldg1)" opacity="0.7"/>
            <rect x="810" y="250" width="90" height="450" fill="url(#bldg1)" opacity="0.7"/>

            {{-- MAIN BANK BUILDING (center, tallest) --}}
            <rect x="330" y="120" width="240" height="580" fill="url(#bldg3)"/>
            {{-- Bank columns --}}
            <rect x="340" y="200" width="12" height="500" fill="rgba(255,255,255,0.04)"/>
            <rect x="365" y="200" width="12" height="500" fill="rgba(255,255,255,0.04)"/>
            <rect x="390" y="200" width="12" height="500" fill="rgba(255,255,255,0.04)"/>
            <rect x="540" y="200" width="12" height="500" fill="rgba(255,255,255,0.04)"/>
            <rect x="555" y="200" width="12" height="500" fill="rgba(255,255,255,0.04)"/>
            {{-- Bank top detail --}}
            <rect x="310" y="110" width="280" height="20" fill="#1e3358"/>
            <rect x="295" y="100" width="310" height="15" fill="#243d6a"/>
            {{-- Bank spire --}}
            <rect x="446" y="30" width="8" height="75" fill="#D4A017" opacity="0.8"/>
            <polygon points="450,15 440,35 460,35" fill="#D4A017" opacity="0.9"/>
            {{-- Glowing top --}}
            <circle cx="450" cy="22" r="4" fill="#D4A017" filter="url(#glow)" opacity="0.9"/>

            {{-- Bank name sign --}}
            <rect x="370" y="145" width="160" height="35" fill="rgba(212,160,23,0.15)" rx="4"/>
            <rect x="372" y="147" width="156" height="31" fill="none" stroke="rgba(212,160,23,0.4)" stroke-width="1" rx="3"/>
            {{-- "HAWKS CREDITS" text simulation with bars --}}
            <rect x="385" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="396" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="407" y="155" width="12" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="422" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="433" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="444" y="155" width="12" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="459" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="470" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="481" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="493" y="155" width="12" height="15" fill="#D4A017" opacity="0.7" rx="1"/>
            <rect x="507" y="155" width="8" height="15" fill="#D4A017" opacity="0.7" rx="1"/>

            {{-- Bank windows grid --}}
            <g fill="rgba(255,220,100,0.35)">
                {{-- Row 1 --}}
                <rect x="350" y="210" width="18" height="25" rx="2"/>
                <rect x="380" y="210" width="18" height="25" rx="2"/>
                <rect x="410" y="210" width="18" height="25" rx="2"/>
                <rect x="470" y="210" width="18" height="25" rx="2"/>
                <rect x="500" y="210" width="18" height="25" rx="2"/>
                <rect x="530" y="210" width="18" height="25" rx="2"/>
                {{-- Row 2 --}}
                <rect x="350" y="250" width="18" height="25" rx="2"/>
                <rect x="380" y="250" width="18" height="25" rx="2"/>
                <rect x="470" y="250" width="18" height="25" rx="2"/>
                <rect x="530" y="250" width="18" height="25" rx="2"/>
                {{-- Row 3 --}}
                <rect x="350" y="290" width="18" height="25" rx="2"/>
                <rect x="410" y="290" width="18" height="25" rx="2"/>
                <rect x="470" y="290" width="18" height="25" rx="2"/>
                <rect x="500" y="290" width="18" height="25" rx="2"/>
                {{-- Row 4 --}}
                <rect x="380" y="330" width="18" height="25" rx="2"/>
                <rect x="410" y="330" width="18" height="25" rx="2"/>
                <rect x="470" y="330" width="18" height="25" rx="2"/>
                <rect x="530" y="330" width="18" height="25" rx="2"/>
                {{-- Row 5 --}}
                <rect x="350" y="370" width="18" height="25" rx="2"/>
                <rect x="500" y="370" width="18" height="25" rx="2"/>
                <rect x="530" y="370" width="18" height="25" rx="2"/>
                {{-- Rows 6-8 --}}
                <rect x="380" y="410" width="18" height="25" rx="2"/>
                <rect x="410" y="410" width="18" height="25" rx="2"/>
                <rect x="470" y="410" width="18" height="25" rx="2"/>
                <rect x="350" y="450" width="18" height="25" rx="2"/>
                <rect x="500" y="450" width="18" height="25" rx="2"/>
                <rect x="410" y="490" width="18" height="25" rx="2"/>
                <rect x="470" y="490" width="18" height="25" rx="2"/>
                <rect x="530" y="490" width="18" height="25" rx="2"/>
            </g>

            {{-- Brightly lit windows --}}
            <g fill="rgba(255,220,100,0.8)" filter="url(#glow)">
                <rect x="440" y="210" width="18" height="25" rx="2"/>
                <rect x="440" y="330" width="18" height="25" rx="2"/>
                <rect x="380" y="370" width="18" height="25" rx="2"/>
                <rect x="440" y="450" width="18" height="25" rx="2"/>
            </g>

            {{-- Left tower --}}
            <rect x="155" y="200" width="130" height="500" fill="url(#bldg1)"/>
            <rect x="145" y="190" width="150" height="15" fill="#152238"/>
            {{-- Left tower windows --}}
            <g fill="rgba(255,220,100,0.3)">
                <rect x="168" y="215" width="15" height="20" rx="1"/>
                <rect x="193" y="215" width="15" height="20" rx="1"/>
                <rect x="218" y="215" width="15" height="20" rx="1"/>
                <rect x="255" y="215" width="15" height="20" rx="1"/>
                <rect x="168" y="248" width="15" height="20" rx="1"/>
                <rect x="243" y="248" width="15" height="20" rx="1"/>
                <rect x="255" y="248" width="15" height="20" rx="1"/>
                <rect x="193" y="281" width="15" height="20" rx="1"/>
                <rect x="218" y="281" width="15" height="20" rx="1"/>
                <rect x="255" y="314" width="15" height="20" rx="1"/>
                <rect x="168" y="347" width="15" height="20" rx="1"/>
                <rect x="193" y="347" width="15" height="20" rx="1"/>
                <rect x="218" y="380" width="15" height="20" rx="1"/>
                <rect x="168" y="413" width="15" height="20" rx="1"/>
                <rect x="243" y="413" width="15" height="20" rx="1"/>
            </g>

            {{-- Right tower --}}
            <rect x="615" y="180" width="135" height="520" fill="url(#bldg3)"/>
            <rect x="605" y="168" width="155" height="16" fill="#1e3358"/>
            {{-- Right tower windows --}}
            <g fill="rgba(255,220,100,0.3)">
                <rect x="628" y="195" width="15" height="20" rx="1"/>
                <rect x="653" y="195" width="15" height="20" rx="1"/>
                <rect x="678" y="195" width="15" height="20" rx="1"/>
                <rect x="718" y="195" width="15" height="20" rx="1"/>
                <rect x="628" y="228" width="15" height="20" rx="1"/>
                <rect x="703" y="228" width="15" height="20" rx="1"/>
                <rect x="718" y="261" width="15" height="20" rx="1"/>
                <rect x="653" y="261" width="15" height="20" rx="1"/>
                <rect x="628" y="294" width="15" height="20" rx="1"/>
                <rect x="678" y="294" width="15" height="20" rx="1"/>
                <rect x="703" y="327" width="15" height="20" rx="1"/>
                <rect x="653" y="360" width="15" height="20" rx="1"/>
                <rect x="628" y="393" width="15" height="20" rx="1"/>
                <rect x="718" y="393" width="15" height="20" rx="1"/>
                <rect x="678" y="426" width="15" height="20" rx="1"/>
            </g>

            {{-- Street level --}}
            <rect x="0" y="580" width="900" height="120" fill="#07111f"/>
            {{-- Road --}}
            <rect x="0" y="595" width="900" height="60" fill="#0a1728"/>
            {{-- Road markings --}}
            <g fill="rgba(212,160,23,0.2)">
                <rect x="50" y="623" width="60" height="3" rx="1"/>
                <rect x="160" y="623" width="60" height="3" rx="1"/>
                <rect x="270" y="623" width="60" height="3" rx="1"/>
                <rect x="380" y="623" width="60" height="3" rx="1"/>
                <rect x="490" y="623" width="60" height="3" rx="1"/>
                <rect x="600" y="623" width="60" height="3" rx="1"/>
                <rect x="710" y="623" width="60" height="3" rx="1"/>
                <rect x="820" y="623" width="60" height="3" rx="1"/>
            </g>
            {{-- Street reflection --}}
            <rect x="330" y="660" width="240" height="40" fill="url(#gold_glow)" opacity="0.15"/>

            {{-- Street lights --}}
            <g>
                <rect x="148" y="530" width="4" height="60" fill="#1a2a4a"/>
                <circle cx="150" cy="528" r="8" fill="rgba(255,220,100,0.6)" filter="url(#glow)"/>
                <rect x="598" y="530" width="4" height="60" fill="#1a2a4a"/>
                <circle cx="600" cy="528" r="8" fill="rgba(255,220,100,0.6)" filter="url(#glow)"/>
            </g>

            {{-- Bank entrance --}}
            <rect x="400" y="520" width="100" height="80" fill="#0d1f3c"/>
            <rect x="410" y="525" width="35" height="75" fill="#0a1728"/>
            <rect x="455" y="525" width="35" height="75" fill="#0a1728"/>
            {{-- Door handles --}}
            <rect x="443" y="555" width="4" height="15" fill="rgba(212,160,23,0.6)" rx="2"/>
            <rect x="453" y="555" width="4" height="15" fill="rgba(212,160,23,0.6)" rx="2"/>
            {{-- Entrance light --}}
            <ellipse cx="450" cy="520" rx="30" ry="8" fill="rgba(212,160,23,0.2)"/>

            {{-- Reflection on wet road --}}
            <rect x="390" y="655" width="120" height="45" fill="#1e3358" opacity="0.3" transform="scale(1,-0.3) translate(0,-2200)"/>
        </svg>

        {{-- Animated window lights overlay --}}
        <div class="window-lights" id="windowLights"></div>

        {{-- Dark overlay --}}
        <div class="left-overlay"></div>

        {{-- Content --}}
        <div class="left-content">
            <div class="left-tagline">
                Powering <span>Financial</span><br>
                Trust &amp; Growth
            </div>
            <p class="left-sub">
                ENTERPRISE CREDIT MANAGEMENT SYSTEM
            </p>
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-number">850</span>
                    <span class="stat-label">Max Credit Score</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">3</span>
                    <span class="stat-label">Access Levels</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Secure &amp; Encrypted</span>
                </div>
            </div>
            <div class="features">
                <span class="feature-pill">Credit Scoring</span>
                <span class="feature-pill">Loan Management</span>
                <span class="feature-pill">Auto Amortization</span>
                <span class="feature-pill">Role-Based Access</span>
                <span class="feature-pill">Real-time Analytics</span>
            </div>
        </div>
    </div>

    {{-- ══ RIGHT PANEL — Login Form ══ --}}
    <div class="right-panel" x-data="{ loading: false, showPass: false }">

        {{-- Logo --}}
        <div class="logo-area">
            <div class="logo-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" fill="#D4A017"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"
                          fill="#D4A017" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="logo-text">
                <h1>Hawks Credits</h1>
                <p>Enterprise Credit Platform</p>
            </div>
        </div>

        {{-- Heading --}}
        <h2 class="form-heading">Welcome Back</h2>
        <p class="form-sub">Sign in to access your account</p>

        {{-- Error message --}}
        @if($errors->any())
        <div class="error-alert">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0;margin-top:1px">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707
                     7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414
                     1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1
                     1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" @submit="loading = true">
            @csrf

            <div class="field">
                <label>Email Address</label>
                <div class="input-wrap">
                    <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005
                                 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                    <input type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email"
                           placeholder="your@email.com">
                </div>
            </div>

            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0
                                 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input :type="showPass ? 'text' : 'password'"
                           name="password" required
                           placeholder="••••••••"
                           style="padding-right: 2.8rem">
                    <button type="button" class="pass-toggle" @click="showPass = !showPass">
                        <svg x-show="!showPass" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                     9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPass" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97
                                     9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242
                                     4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0
                                     0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0
                                     01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="row-options">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Keep me signed in</span>
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot">
                    Forgot password?
                </a>
                @endif
            </div>

            <button type="submit" class="btn-submit" :disabled="loading">
                <div x-show="loading" class="spinner"></div>
                <span x-text="loading ? 'Signing in...' : 'Sign In to Hawks Credits'"></span>
            </button>
        </form>

        @if(Route::has('register'))
        <div class="divider">
            <div class="divider-line"></div>
            <span>OR</span>
            <div class="divider-line"></div>
        </div>
        <p class="register-link">
            Don't have an account?
            <a href="{{ route('register') }}">Create Account</a>
        </p>
        @endif

        <p class="form-footer">
            © {{ date('Y') }} HAWKS CREDITS · SECURE · ENCRYPTED · ENTERPRISE GRADE
        </p>
    </div>

    <script>
        // Generate random blinking window lights on the cityscape
        const container = document.getElementById('windowLights');
        if (container) {
            for (let i = 0; i < 20; i++) {
                const dot = document.createElement('div');
                dot.className = 'window-light';
                dot.style.cssText = `
                    left: ${Math.random() * 55}%;
                    top: ${20 + Math.random() * 55}%;
                    --dur: ${2 + Math.random() * 4}s;
                    --delay: -${Math.random() * 4}s;
                    width: ${2 + Math.random() * 3}px;
                    height: ${2 + Math.random() * 3}px;
                `;
                container.appendChild(dot);
            }
        }
    </script>

</body>
</html>