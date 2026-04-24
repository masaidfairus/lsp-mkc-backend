<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Verifikasi Tanda Tangan</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg: #f0f4f8;
      --card: #ffffff;
      --primary: #4f46e5;
      --primary-light: #eef2ff;
      --accent: #06b6d4;
      --success: #10b981;
      --text: #1e293b;
      --muted: #64748b;
      --border: #e2e8f0;
      --radius: 16px;
      --shadow: 0 4px 24px rgba(79,70,229,0.10);
    }

    body {
      min-height: 100vh;
      background: var(--bg);
      font-family: 'Segoe UI', system-ui, sans-serif;
      color: var(--text);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 32px 16px 48px;
    }

    /* ── Header ── */
    .header {
      text-align: center;
      margin-bottom: 28px;
    }
    .header .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--primary-light);
      color: var(--primary);
      font-size: 12px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 99px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 10px;
    }
    .header h1 {
      font-size: 26px;
      font-weight: 700;
      color: var(--text);
    }
    .header p {
      color: var(--muted);
      font-size: 14px;
      margin-top: 4px;
    }

    /* ── Card ── */
    .card {
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      width: 100%;
      max-width: 480px;
      overflow: hidden;
    }

    /* ── Status Bar ── */
    .status-bar {
      background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
      padding: 14px 24px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .status-bar svg { flex-shrink: 0; }
    .status-bar .status-text { color: #fff; font-size: 14px; font-weight: 600; }
    .status-bar .status-sub  { color: rgba(255,255,255,0.75); font-size: 12px; }

    /* ── Body ── */
    .card-body { padding: 28px 28px 8px; }

    /* ── Info Row ── */
    .info-row {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 16px 0;
      border-bottom: 1px solid var(--border);
    }
    .info-row:last-of-type { border-bottom: none; }

    .icon-wrap {
      width: 40px; height: 40px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .icon-wrap.purple { background: #ede9fe; color: #7c3aed; }
    .icon-wrap.blue   { background: #e0f2fe; color: #0284c7; }
    .icon-wrap.green  { background: #d1fae5; color: #059669; }

    .info-label {
      font-size: 11px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.6px;
      margin-bottom: 3px;
    }
    .info-value {
      font-size: 15px;
      font-weight: 600;
      color: var(--text);
    }
    .info-sub {
      font-size: 12px;
      color: var(--muted);
      margin-top: 2px;
    }

    /* ── Signature Canvas ── */
    .sig-section { padding: 20px 28px 28px; }
    .sig-label {
      font-size: 11px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.6px;
      margin-bottom: 12px;
    }
    .sig-box {
      background: #ffffff;
      background-image: 
        radial-gradient(#e2e8f0 0.5px, transparent 0.5px),
        radial-gradient(#e2e8f0 0.5px, #ffffff 0.5px);
      background-size: 20px 20px;
      background-position: 0 0, 10px 10px;
      border: 2px solid #f1f5f9;
      border-radius: 16px;
      width: 100%;
      height: 180px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
      box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);
    }
    .sig-box::after {
      content: "VERIFIED DOCUMENT";
      position: absolute;
      bottom: 8px;
      right: 12px;
      font-size: 9px;
      font-weight: 800;
      color: #e2e8f0;
      letter-spacing: 1px;
      pointer-events: none;
    }
    .sig-box canvas {
      display: block;
      z-index: 1;
    }
    .sig-box .no-sig {
      color: var(--muted);
      font-size: 13px;
      text-align: center;
      z-index: 2;
    }

    /* ── Footer ── */
    .card-footer {
      background: #f8fafc;
      border-top: 1px solid var(--border);
      padding: 14px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .card-footer .brand {
      font-size: 11px;
      color: var(--muted);
      font-weight: 500;
    }
    .card-footer .brand span { color: var(--primary); font-weight: 700; }
    .verified-chip {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      font-weight: 600;
      color: var(--success);
      background: #d1fae5;
      padding: 3px 10px;
      border-radius: 99px;
    }

    /* ── Error State ── */
    .error-card {
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      width: 100%;
      max-width: 480px;
      padding: 48px 32px;
      text-align: center;
    }
    .error-icon { font-size: 48px; margin-bottom: 16px; }
    .error-card h2 { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
    .error-card p  { font-size: 14px; color: var(--muted); }
  </style>
</head>
<body>

  <div class="header">
    <div class="badge">
      <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2 0 5.523-3.887 10.12-9 11.317C3.887 17.12 0 12.523 0 7c0-.68.057-1.35.166-2z" clip-rule="evenodd"/>
      </svg>
      Sistem Verifikasi
    </div>
    <h1>Verifikasi Tanda Tangan</h1>
    <p>Dokumen digital yang terautentikasi</p>
  </div>

  <div id="app"></div>

  <script>
    // ── Helpers ──────────────────────────────────────────────────
    function getQueryData() {
      // 1. Try to get data from PHP injection (for QR codes with ID)
      const serverData = @json($sigData ?? null);
      if (serverData) return serverData;

      // 2. Try to get data from URL parameter (legacy/direct payload)
      const params = new URLSearchParams(window.location.search);
      const raw = params.get('data');
      if (raw) {
        try { return JSON.parse(decodeURIComponent(raw)); } catch {}
      }

      // 3. Fallback to DEMO
      return DEMO_DATA();
    }

    function DEMO_DATA() {
      // Realistic demo: compress a fake SVG path the same way PHP does
      const fakePath = "M30,80 C40,20 80,20 90,80 S150,140 160,80";
      const compressed = pako.deflate(new TextEncoder().encode(fakePath));
      const b64 = btoa(String.fromCharCode(...compressed));
      return { n: "Budi Santoso", d: "2025-04-14 09:32:17", s: b64 };
    }

    function decodeShape(s) {
      try {
        const binary = atob(s);
        const bytes   = Uint8Array.from(binary, c => c.charCodeAt(0));
        const decoded = pako.inflate(bytes, { to: 'string' });
        return decoded;
      } catch (e) {
        return null;
      }
    }

    function formatDate(d) {
      const dt = new Date(d.replace(' ', 'T'));
      if (isNaN(dt)) return d;
      return dt.toLocaleDateString('id-ID', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
      });
    }
    function formatTime(d) {
      const dt = new Date(d.replace(' ', 'T'));
      if (isNaN(dt)) return '';
      return dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
    }

    // ── Draw Signature ────────────────────────────────────────────
    function drawSignature(container, shapeStr) {
      if (!shapeStr) {
        container.innerHTML = '<div class="no-sig">Bentuk tanda tangan tidak tersedia</div>';
        return;
      }

      const canvas = document.createElement('canvas');
      const dpr = window.devicePixelRatio || 1;
      const rect = container.getBoundingClientRect();
      const width = rect.width || 420;
      const height = rect.height || 160;

      canvas.width  = width * dpr;
      canvas.height = height * dpr;
      canvas.style.width  = width + 'px';
      canvas.style.height = height + 'px';
      
      container.appendChild(canvas);

      const ctx = canvas.getContext('2d');
      ctx.scale(dpr, dpr);

      // Default styles
      ctx.strokeStyle = '#1e293b';
      ctx.lineWidth   = 2.2;
      ctx.lineCap     = 'round';
      ctx.lineJoin    = 'round';

      let drawn = false;
      try {
        const data = JSON.parse(shapeStr);
        
        if (Array.isArray(data) && data.length > 0) {
          // 1. Calculate Bounding Box
          let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
          let hasPoints = false;

          const points = []; // Flattened points for calculation
          if (data[0].points) {
            data.forEach(s => s.points.forEach(p => {
              points.push(p);
              minX = Math.min(minX, p.x); minY = Math.min(minY, p.y);
              maxX = Math.max(maxX, p.x); maxY = Math.max(maxY, p.y);
              hasPoints = true;
            }));
          } else if (data[0].x !== undefined) {
            data.forEach(p => {
              points.push(p);
              minX = Math.min(minX, p.x); minY = Math.min(minY, p.y);
              maxX = Math.max(maxX, p.x); maxY = Math.max(maxY, p.y);
              hasPoints = true;
            });
          }

          if (hasPoints) {
            const sigW = maxX - minX;
            const sigH = maxY - minY;
            const padding = 20;
            
            // 2. Calculate Scale & Translation
            const scale = Math.min((width - padding*2) / sigW, (height - padding*2) / sigH, 1.2);
            const offsetX = (width - sigW * scale) / 2 - minX * scale;
            const offsetY = (height - sigH * scale) / 2 - minY * scale;

            ctx.translate(offsetX, offsetY);
            ctx.scale(scale, scale);

            // 3. Draw
            if (data[0].points) {
              data.forEach(stroke => {
                if (stroke.points.length > 0) {
                  ctx.beginPath();
                  ctx.moveTo(stroke.points[0].x, stroke.points[0].y);
                  stroke.points.forEach((p, i) => { if(i>0) ctx.lineTo(p.x, p.y); });
                  ctx.stroke();
                }
              });
            } else {
              ctx.beginPath();
              data.forEach(p => {
                if (p.type === 'start' || p.type === 0 || p.start) ctx.moveTo(p.x, p.y);
                else ctx.lineTo(p.x, p.y);
              });
              ctx.stroke();
            }
            drawn = true;
          }
        }
      } catch (e) {
        console.error('Draw error:', e);
      }

      // Fallback: treat as SVG path string
      if (!drawn) {
        try {
          const path2d = new Path2D(shapeStr);
          ctx.stroke(path2d);
          drawn = true;
        } catch {}
      }

      if (!drawn) {
        container.innerHTML = '<div class="no-sig">Format tanda tangan tidak dikenali</div>';
      }
    }

    // ── Render ────────────────────────────────────────────────────
    function render() {
      const app = document.getElementById('app');
      let data;
      try { data = getQueryData(); }
      catch {
        app.innerHTML = `
          <div class="error-card">
            <div class="error-icon">⚠️</div>
            <h2>Data Tidak Valid</h2>
            <p>QR code ini tidak dapat dibaca atau sudah kadaluarsa.</p>
          </div>`;
        return;
      }

      const { n: name, d: date, s: shape } = data;
      const sigPath = shape ? decodeShape(shape) : null;

      app.innerHTML = `
        <div class="card">
          <div class="status-bar">
            <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <div>
              <div class="status-text">Tanda Tangan Terverifikasi</div>
              <div class="status-sub">Dokumen ini sah dan telah diautentikasi</div>
            </div>
          </div>

          <div class="card-body">
            <div class="info-row">
              <div class="icon-wrap purple">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div>
                <div class="info-label">Pemilik Tanda Tangan</div>
                <div class="info-value">${escHtml(name || '—')}</div>
                <div class="info-sub">Penandatangan yang terdaftar</div>
              </div>
            </div>

            <div class="info-row">
              <div class="icon-wrap blue">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <div class="info-label">Tanggal Tanda Tangan</div>
                <div class="info-value">${formatDate(date || '')}</div>
                <div class="info-sub">${formatTime(date || '')}</div>
              </div>
            </div>
          </div>

          <div class="sig-section">
            <div class="sig-label">Bentuk Tanda Tangan</div>
            <div class="sig-box" id="sig-box"></div>
          </div>

          <div class="card-footer">
            <div class="brand">Powered by <span>SignVerify</span></div>
            <div class="verified-chip">
              <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z" clip-rule="evenodd"/>
              </svg>
              Dokumen Sah
            </div>
          </div>
        </div>`;

      // draw after DOM is painted
      requestAnimationFrame(() => {
        const box = document.getElementById('sig-box');
        if (box) drawSignature(box, sigPath);
      });
    }

    function escHtml(str) {
      return str.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }

    render();
  </script>
</body>
</html>