<?php
$base_dir = $base_dir ?? './';
?>
  <!-- ── FOOTER ── -->
  <style>
    .footer {
      background-color: #020617; /* Slate 950 */
      color: #94A3B8; /* Slate 400 */
      padding: 64px 0 32px;
      font-family: 'Inter', sans-serif;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      position: relative;
      margin-top: auto; /* Push to bottom if body is flex */
    }
    
    .footer .container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1.5fr;
      gap: 32px;
      margin-bottom: 48px;
    }
    
    /* Responsividad básica para el footer */
    @media (max-width: 1024px) {
      .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 48px;
      }
    }
    @media (max-width: 600px) {
      .footer-grid {
        grid-template-columns: 1fr;
      }
    }

    .footer-brand-name {
      font-family: 'Outfit', sans-serif;
      font-size: 1.5rem;
      font-weight: 800;
      color: #F8FAFC;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .footer-brand-desc {
      line-height: 1.6;
      font-size: 0.95rem;
      max-width: 400px;
      margin-bottom: 24px;
    }

    .footer-social {
      display: flex;
      gap: 16px;
    }
    
    .footer-social a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255,255,255,0.05);
      color: #94A3B8;
      transition: all 0.3s ease;
      text-decoration: none;
    }
    
    .footer-social a:hover {
      background: #3B82F6;
      color: #FFF;
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
    }

    .footer-col-title {
      color: #F8FAFC;
      font-family: 'Outfit', sans-serif;
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 20px;
      position: relative;
    }

    .footer-col-title::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -8px;
      width: 24px;
      height: 2px;
      background: #3B82F6;
      border-radius: 2px;
    }

    .footer-col ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .footer-col a {
      color: #94A3B8;
      text-decoration: none;
      transition: color 0.2s ease;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .footer-col a i {
      font-size: 0.8rem;
      color: #475569;
      transition: color 0.2s ease;
    }

    .footer-col a:hover {
      color: #3B82F6; /* Accent Blue */
    }
    .footer-col a:hover i {
      color: #3B82F6;
    }

    .footer-contact-info {
      display: flex;
      flex-direction: column;
      gap: 12px;
      font-size: 0.95rem;
    }
    .footer-contact-info div {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .footer-contact-info i {
      color: #3B82F6;
    }

    .footer-bottom {
      padding-top: 32px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.85rem;
      flex-wrap: wrap;
      gap: 16px;
    }
  </style>

  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        
        <div class="footer-col">
          <div class="footer-brand-name"><span>🌟</span> PR_X Academy</div>
          <p class="footer-brand-desc">
            Plataforma educativa inclusiva diseñada para estudiantes y profesores. 
            Aprende a tu propio ritmo en un entorno estructurado, amigable y libre de sobreestimulación.
          </p>
          <div class="footer-social">
            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" title="Discord"><i class="fab fa-discord"></i></a>
          </div>
        </div>
        
        <div class="footer-col">
          <div class="footer-col-title">Plataforma</div>
          <ul>
            <li><a href="<?= htmlspecialchars($base_dir) ?>pages/register.php"><i class="fas fa-chevron-right"></i> Crear Cuenta</a></li>
            <li><a href="<?= htmlspecialchars($base_dir) ?>pages/login.php"><i class="fas fa-chevron-right"></i> Iniciar Sesión</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Para Alumnos</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Para Profesores</a></li>
          </ul>
        </div>
        
        <div class="footer-col">
          <div class="footer-col-title">Explorar</div>
          <ul>
            <li><a href="#features"><i class="fas fa-chevron-right"></i> Características</a></li>
            <li><a href="#subjects"><i class="fas fa-chevron-right"></i> Áreas de Aprendizaje</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Guía Rápida</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Blog Educativo</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <div class="footer-col-title">Atención al Usuario</div>
          <div class="footer-contact-info">
            <div><i class="fas fa-envelope"></i> soporte@prxacademy.com</div>
            <div><i class="fas fa-phone-alt"></i> +34 900 123 456</div>
            <div><i class="fas fa-map-marker-alt"></i> Sede Principal Virtual, Web</div>
          </div>
          <div style="margin-top: 20px;">
            <a href="#" style="color: #94A3B8; font-size: 0.9rem; text-decoration: underline;">Términos y Privacidad</a>
          </div>
        </div>
        
      </div>
      
      <div class="footer-bottom">
        <span>© <?= date('Y') ?> PR_X Academy. Todos los derechos reservados.</span>
        <span>Diseño Inclusivo y Accesible para todos.</span>
      </div>
    </div>
  </footer>

  <script src="<?= htmlspecialchars($base_dir) ?>js/data.js"></script>
  <script src="<?= htmlspecialchars($base_dir) ?>js/storage.js"></script>
  <?php if (isset($extra_scripts)) echo $extra_scripts; ?>
</body>
</html>
