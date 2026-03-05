<svg viewBox="0 0 320 160" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <defs>
    <linearGradient id="vGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1e90ff"/>
      <stop offset="100%" stop-color="#00c6ff"/>
    </linearGradient>

    <linearGradient id="tGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#eeeeee"/>
      <stop offset="100%" stop-color="#adb5bd"/>
    </linearGradient>

    <filter id="shadow" x="-30%" y="-30%" width="160%" height="160%">
      <feDropShadow dx="2" dy="2" stdDeviation="3" flood-opacity="0.2"/>
    </filter>
  </defs>

  <g filter="url(#shadow)">
    <!-- V Shape -->
    <path d="M20 20 L90 20 L75 140 L45 140 Z" fill="url(#vGradient)"/>
    <path d="M90 20 L160 20 L115 140 L75 140 Z" fill="url(#vGradient)"/>

    <!-- T Shape -->
    <path d="M125 20 L145 20 L300 20 L285 55 L160 55 Z" fill="url(#tGradient)"/>
    <path d="M175 55 L215 55 L195 140 L155 140 Z" fill="url(#tGradient)"/>
  </g>
</svg>
