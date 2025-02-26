# Moodle

<p align="center"><a href="https://moodle.org" target="_blank" title="Moodle Website">
  <img src="https://raw.githubusercontent.com/moodle/moodle/main/.github/moodlelogo.svg" alt="The Moodle Logo">
</a></p>

[Moodle]# 📊 Moodle Report Plugin

## 📖 **Überblick**
Dieses Repository enthält ein **Moodle-Report-Plugin**, das zur Erfüllung einer **Testaufgabe** entwickelt wurde.  
Das Plugin analysiert und generiert Berichte über das Nutzerverhalten und Aktivitäten innerhalb von Moodle-Kursen.

🔍 **Zweck des Plugins:**
1. **Erfassen und Anzeigen relevanter Nutzungsdaten** für Administratoren.
2. **Automatisierte Generierung von Berichten**, basierend auf Kursaktivitäten.
3. **Sichere Speicherung von Konfigurationsdaten**, u.a. für SMTP-Zugangsdaten.
4. **Optimierte Darstellung von Kurs-, Quiz- und Forenaktivitäten**.

---

# Anleitung zur Einrichtung und Nutzung des Moodle Report-Plugins

Diese Anleitung bietet eine schrittweise Erklärung zur Einrichtung und Nutzung des Report-Plugins.
Sie ist für Anwender konzipiert, die die Anwendung zum ersten Mal aufsetzen.

## Voraussetzungen

Bevor Sie beginnen, stellen Sie sicher, dass folgende Software auf Ihrem System installiert ist:

- **PHP 8.2+**
- **MariaDB**: (v.10.6.7+)
- **Composer** 
  
## Projekt herunterladen

1. **Klonen Sie das Repository**:
   Öffnen Sie ein Terminal oder eine Eingabeaufforderung und führen Sie folgenden Befehl aus:

   ```bash
   git clone https://github.com/Davicito81/moodle_report-plugin.git
   cd moodle_report-plugin
   ```
   Falls Git nicht installiert ist, können Sie das Projekt auch als ZIP-Datei direkt von GitHub herunterladen:

   Besuchen Sie https://github.com/Davicito81/moodle_report-plugin
   Klicken Sie auf Code und dann auf Download ZIP
   Entpacken Sie die ZIP-Datei und navigieren Sie in das Projektverzeichnis.

## Projekt-Testumgebung herstellen 
   Nach dem das Projekt heruntergeladen wurde, müssen wichtige Abhänigkeiten nachinstalliert werden.
   Dazu füren Sie folgenden Befehl aus.
   
   ```bash
   composer install
   ```
   

   Hinweis für die lokale Verwendung der Rest-API 
   - Zum Ausführen von Moodel, muss die PHP-8.1+ vorhanden werden und
   - die notwendige PHP-Extension: cli, mbstring, xml, curl, mysql, gd, zim, sodium, soap, intl
   - Fals notwendif muss der PHPMailer via Composer hinzugefügt werden, da der Mailversand damit
     umgesetzt wurde.
     ```bash
     composer require phpmailer/phpmailer   
     ```


## Moodle-Konfigurationsdatei anpassen
Wichtige Sicherheitshinweise:

Sensible Daten wie SMTP-Zugangsdaten dürfen nicht in config.php hinterlegt werden, sondern ausschließlich in .env.
Keine Passwörter oder API-Keys direkt in den Code einfügen!
- Dazu Liegt im Moodle-Rootverzeichnis eine ".env_config" di in in ".env" umbenannt werden muss
- Darin enthalten müssen die Zuganmgsdaten für die Datenbank und für das SMPT-Konto hinterlegt werden, damit der E-Mail-Versant funktionieren kann! 
  
## Testdaten vorbereiten in Moodle hinterlegen, falls nicht vorhanden
Damit das Plugin korrekt funktioniert, müssen Testkurse und Aktivitäten angelegt werden, darunter:

📚 Kurse
✍ Quizzes
💬 Foren
🎓 Lektionen
Ohne diese Testdaten können keine sinnvollen Berichte erstellt werden!


## Lizenz & Mitwirken
Dieses Projekt wurde für eine Testaufgabe erstellt und ist nicht für den produktiven Einsatz vorgesehen.
Für Rückfragen oder Verbesserungsvorschläge gerne ein Issue erstellen oder einen Pull Request senden.

📌 Autor: David Izaguirre
🛠 Entwickelt für Moodle Testumgebungen
📅 Letztes Update: Februar 2025

