---
stitch-project-id: "[pending]"
---
# Project Vision & Constitution: Bruce Class

> **AGENT INSTRUCTION:** Read this file before every iteration. It is the project's long-term memory. Read `.stitch/DESIGN.md` next, and copy its Section 6 block into every Stitch prompt. If `.stitch/next-prompt.md` is empty or already done, take the highest-priority unchecked item from Section 5.
>
> **LOOP STATUS: NOT STARTED.** The setup is complete. Do not run `stitch-loop` until the project owner explicitly says to.

---

## 1. Core Identity

* **Project Name:** Bruce Class
* **Stitch Project ID:** `[pending]`. No Stitch project exists yet. The first loop iteration creates it (see `.stitch/README.md`), saves it to `.stitch/metadata.json` and replaces this placeholder.
* **Mission:** Turn Bruce Class into a premium, interactive school and college website with one connected digital campus, so that prospective students can explore and apply, and students, teachers and staff can run their school life through role-based portals.
* **Target Audience:**
  * **Prospective students** (senior high graduates, transferees) and their **parents or guardians**: discovery, trust and admissions.
  * **Current students:** grades, schedule, payments, announcements.
  * **Teachers:** classes, attendance, grading.
  * **Registrar, Cashier and Administrators:** records, enrollment, payments, content, users.
  * **Community, alumni and partners:** news, events, reputation.
* **Voice:** Confident, warm, precise and institutional. Short declarative sentences. Never boastful, and no invented facts.
* **Signature line:** "Where ambition becomes achievement."
* **Quality bar:** It should look like a custom site from a top-tier agency (a "$10,000 premium interactive website"). The quality comes from typography, spacing, composition, interaction and consistency, not decoration.

---

## 2. Visual Language (Stitch Prompt Strategy)

*Describe designs in words when prompting Stitch, never in code. All tokens live in `.stitch/DESIGN.md`.*

* **The "Vibe" (Adjectives):**
  * *Primary:* **Editorial.** Large light serif headlines, generous whitespace, magazine-grade composition.
  * *Secondary:* **Institutional.** Trustworthy, structured, Swiss-grid, calm.
  * *Tertiary:* **Digitally fluent.** Modern product UI in the portals, subtle and purposeful motion.
* **Color Philosophy (semantic):**
  * **Backgrounds:** Warm Paper (#F6F4EF), alternating with Clean Surface White (#FFFFFF). At most one Night Ink (#0F1522) band per page, plus the footer.
  * **Action:** Harbor Navy (#1C3B5A) is the only interactive colour.
  * **Signature detail:** Heritage Brass (#A87A2E) hairline rules and Burnished Brass (#86601F) mono kickers.
  * **Text:** Midnight Ink (#151B28) and Slate Muted (#5A6272).
* **Type:** Fraunces (display), Geist (UI and body), Geist Mono (labels and data).
* **Must avoid:** Bootstrap/AdminLTE look, SaaS-dashboard clichés, children's-site colours, government-portal dullness, gradients and glassmorphism, invented statistics.

---

## 3. Architecture & File Structure

### 3.1 Existing application (inspected during setup, preserve it)

Bruce Class is a **PHP 8 + PDO/MariaDB** application (XAMPP). Its admin side uses **AdminLTE 3 / Bootstrap 4**, DataTables and SweetAlert2. The code base is still branded for its original school ("St. Joseph Catholic School of Sagay Inc.", database `stjoseph_db`). The rebrand to **Bruce Class** happens in the design layer. **Do not rename databases, tables, PHP constants or session keys** as part of the design loop.

| Area | Existing files | Notes |
|---|---|---|
| Public home | `index.php` → `homepage_public.php`, `theme/public_header.php`, `theme/public_footer.php`, `public.css` | Anonymous visitors see the public home, and signed-in users see the admin dashboard (`home.php` + `theme/template.php`). |
| News article | `article.php` | Reads `tblnews` and `tblnews_media` (block types Text, Image, Video, Caption with `SORT_ORDER`, which supports text above and below media). |
| Admissions | `apply.php`, `portal/applicant/*` (admission form, payment) | Tables `tblapplicants`, `tblentrance_exams`, `tblexam_results`, `tbladmission_payments`, `tbladmission_receipts`, `tbldocuments`. |
| Portal hub | `portals.php`, `login.php`, `logout.php` | `include/rbac.php` defines the roles Administrator, Staff, Registrar, Cashier, Teacher, Student and Applicant. |
| Student portal | `student-portal.php` (currently "Coming soon") | `rbac.php` routes students to `portal/student/index.php`, **which does not exist yet**. |
| Teacher portal | `portal/teacher/*` | `tblclass_schedules`, `tblclass_attendance`, `tblgrades`, `tblteacher_subjects`. |
| Registrar portal | `portal/registrar/*` (applicants) | `tblstudent`, `tblenrollment`, `tbldocuments`. |
| Cashier portal | `portal/cashier/*` (admission payments) | `tblpayments`, `tblfeeschedule`, `tblfee_types`. |
| Administration | `module/*` (user, usertype, student, course, subject, enrollment, payments, attendance, setschedule, generic, about) | AdminLTE modules. |
| CMS data | `tblhomepage_sections`, `tblhomepage_content_blocks`, `tblannouncements` (with `AUDIENCE`), `tblnews`, `tblnotifications`, `tblsettings` | These power the dynamic home, news and announcements. |
| Programs data | `tblcourses`, `tblmajors`, `tblsubjects` | The seed data holds basic-education levels (Nursery to Grade 10). **The college programs in Section 4.4 must be added as data** before they can go live. |

### 3.2 Design-loop structure (added by this setup)

```
bruce-class/
├── .stitch/
│   ├── README.md          # How this setup works; how to start the loop
│   ├── SITE.md            # This file — vision, architecture, sitemap, roadmap
│   ├── DESIGN.md          # Design system + Stitch prompt block (Section 6)
│   ├── next-prompt.md     # The baton — first task, ready but NOT started
│   ├── metadata.json      # Created by the first loop iteration (Stitch project + screen IDs)
│   └── designs/           # Staging: Stitch output (and local reference drafts)
│       ├── index.html     # Home — local reference draft v0 (not Stitch-generated)
│       ├── index.png      # Desktop screenshot, 1440px
│       └── index-mobile.png
└── site/public/           # Static, integrated prototype pages (created by the loop)
```

* **Root for integrated prototypes:** `site/public/`. These are static HTML files served at `/site/public/` by the same Apache host. They do not replace any PHP file.
* **Asset flow:** Stitch generates a page → it is saved to `.stitch/designs/{page}.html` and `.png` → reviewed → moved to `site/public/{page}.html` with navigation wired → approved pages are later **ported into PHP templates**, one at a time, in a separate step the owner approves (see Section 5, Phase C).
* **Navigation strategy (every public page):**
  * **Global header:** Bruce Class monogram and wordmark; Home, About, Academics, Admissions, Campus Life, Announcements, Events; ghost "Apply" button and primary "Student Portal" button; full-screen drawer on mobile and tablet.
  * **Global footer (Night Ink):** Tagline and email signup; School (About, Academics, Faculty, News, Events), Admissions (How to apply, Requirements, Tuition & fees, Scholarships), Portals (Student, Teacher, Registrar, Cashier, Contact); address, phone and email bar.
  * **Portal shell (every portal page):** Left sidebar (collapsible; bottom tab bar on mobile), top bar with search, notifications and profile menu, content area on Warm Paper, Geist and Geist Mono only.
* **File naming:** Lowercase and hyphenated. Portal screens are prefixed by role (`student-dashboard.html`, `teacher-attendance.html`).

---

## 4. Live Sitemap & Page Architecture

*Update the checkboxes when a page is generated in Stitch **and** integrated into `site/public/`.*

### 4.0 Sitemap checklist

**Public website**
* [ ] `index.html`: Home. *A local reference draft exists in `.stitch/designs/index.html`, but it has not been generated in Stitch or integrated yet. This is the first baton.*
* [ ] `about.html`: About Bruce Class
* [ ] `academics.html`: Academics overview
* [ ] `programs.html`: Program discovery (all programs)
* [ ] `program-detail.html`: Program detail template (BSIT as the sample)
* [ ] `admissions.html`: Admissions overview and process
* [ ] `apply.html`: Enrollment application (multi-step)
* [ ] `campus-life.html`: Campus life
* [ ] `announcements.html`: Announcements feed
* [ ] `news.html`: Newsroom index
* [ ] `article.html`: News article template
* [ ] `events.html`: Events calendar
* [ ] `faculty.html`: Faculty directory
* [ ] `contact.html`: Contact and directions
* [ ] `login.html`: Unified portal sign-in

**Portals**
* [ ] `student-dashboard.html` (plus profile, grades, attendance, schedule, enrollment, payments, notifications)
* [ ] `teacher-dashboard.html` (plus classes, students, attendance, grades, subjects, announcements)
* [ ] `registrar-dashboard.html` (plus records, enrollment, verification, documents, reports)
* [ ] `cashier-dashboard.html` (plus payments, fees, receipts, history, balances, reports)
* [ ] `admin-dashboard.html` (plus users, roles, permissions, announcements, website content, settings, reports)

---

### 4.1 Global page rules (apply to every page below)

Every page must answer three questions above the fold: **Where am I?** (navigation state plus page title), **What is this page about?** (kicker, H1, one-line lede), and **What should I do next?** (one primary action).

* One `h1` per page. Every section opens with a kicker and an H2.
* Each page mixes at least three different compositions (see DESIGN.md §5, "Rhythm").
* Every page ends with a contextual CTA band and then the global footer.
* Mobile layouts are designed on their own, never just shrunk.
* Content comes from the database where a table exists, and placeholders stay visible until real content is supplied.

---

### 4.2 HOME (`index.html` → today `homepage_public.php`)

* **Purpose:** Establish Bruce Class as a premium institution within five seconds, then route each visitor type to their next step.
* **Target users:** Prospective students and parents (primary); current students and staff going to the portal; the community.
* **Major sections and hierarchy:**
  1. **Navigation:** Transparent over the hero, turning into a translucent paper bar with blur on scroll.
  2. **Hero:** An asymmetric split. On the left: kicker ("Admissions for S.Y. [year] are open"), a display headline "Where ambition becomes *achievement.*", a two-line lede, a primary "Start your application" button and a text link "Explore programs". On the right: an image mosaic (tall library photo, faculty photo, and a white "Enrollment open / deadline" status note with a pulsing dot). Below both, a ruled row of four value props.
  3. **Brand introduction:** A 3/9 split with the kicker on the left and a large serif statement (half ink, half muted), two supporting columns and an "Our story" link.
  4. **Featured announcements:** A white band with a lead announcement (image, "Featured" badge, title, summary) beside a ruled list of four dated notices.
  5. **Academic programs:** Filter chips (All, Technology, Education, Business & Tourism, Health), a ruled program index (code, title, degree and duration, circular arrow), and a sticky preview image that changes on hover (desktop only).
  6. **Student experience:** A zig-zag of two rows (Learning and Community), each with an image, copy and a small ruled facts list.
  7. **Digital campus / portal preview:** A dark band. On the left, a role tablist (Student, Teacher, Registrar, Cashier). On the right, a browser-framed portal mock that cross-fades per role. The CTA is "Sign in to the portal".
  8. **Campus highlights:** A horizontal snap carousel of facility cards with previous/next icon buttons and alternating card heights.
  9. **Statistics / achievements:** A four-up ruled stat row using `[metric]` placeholders until the registrar verifies the figures.
  10. **Newsroom:** A 7/5 magazine grid (a feature story beside three thumbnail stories), then a full-width video feature with a poster, play button and copy.
  11. **Events:** A ruled agenda with a large date, title and description, location and time, and an arrow.
  12. **Call to action:** A Harbor Navy panel with "Your next chapter starts here.", four numbered admission steps, "Apply now" (inverse) and "Talk to admissions" (ghost).
  13. **Footer.**
* **Primary action:** Start your application. **Secondary actions:** Explore programs, Sign in to the portal, Read news, View calendar.
* **Key components:** Nav bar and drawer, hero mosaic, status note, editorial lists, filter chips, program index with hover preview, zig-zag rows, role tabs with portal mock, snap carousel, stat rule, magazine grid, video feature, agenda list, CTA panel, footer.
* **Responsive:** Mobile stacks the hero (headline, full-width CTA, mosaic below), hides the sticky program preview, turns the portal sidebar into a scrolling tab strip, shows 82%-wide carousel cards, lays stats out 2×2 and drops the event location column.
* **Relationships:** Links out to Programs and Program detail, Admissions and Apply, Announcements, News and Article, Events, Campus Life, About, and Login and the Portals.
* **Data:** `tblhomepage_sections` and `tblhomepage_content_blocks` (section order and toggles), `tblannouncements` (audience All), `tblnews` (featured and recent), `tblcourses` and `tblmajors`, events (a new `tblevents` table is proposed, see Section 5).

### 4.3 ABOUT (`about.html`)

* **Purpose:** Build trust through the institution's story, mission, leadership and accreditation.
* **Target users:** Parents, prospective students, partners.
* **Sections:** Page hero (kicker, H1 "About Bruce Class", lede, wide campus photo) → Mission, Vision and Core Values (a ruled three-row list, not three cards) → History timeline (a horizontal ruled timeline on desktop, vertical on mobile, with `[year]` placeholders) → Leadership (a portrait grid, 4 across on desktop and 2 on mobile, with name, title and a short line) → Accreditation and recognitions (a logo rule with captions, placeholders only) → Campus and location teaser (map image and address) → CTA "Visit campus / Apply".
* **Primary action:** Apply. **Secondary actions:** Contact, Faculty, Campus life.
* **Components:** Page hero, statement block, timeline, portrait cards, logo rule, CTA panel.
* **Responsive:** The timeline becomes vertical and the leadership grid becomes 2 columns.
* **Relationships:** Faculty, Campus Life, Contact, Admissions. Data comes from the `module/about` content where it applies.

### 4.4 ACADEMICS (`academics.html`) and PROGRAMS (`programs.html`, `program-detail.html`)

* **Purpose:** Let students discover and compare programs, then move confidently into admissions.
* **Target users:** Prospective students, parents, guidance counselors.
* **Program catalogue (to be added to `tblcourses`/`tblmajors`, grouped by discipline):**

| Discipline | Code | Program | Degree type | Duration |
|---|---|---|---|---|
| Technology | BSIT | Information Technology | Bachelor of Science | 4 years |
| Education | BSED-MATH | Secondary Education, Mathematics | Bachelor of Secondary Education | 4 years |
| Education | BSED-FIL | Secondary Education, Filipino | Bachelor of Secondary Education | 4 years |
| Education | BSED-ENG | Secondary Education, English | Bachelor of Secondary Education | 4 years |
| Education | BEED | Elementary Education | Bachelor of Elementary Education | 4 years |
| Business & Tourism | BSTM | Tourism Management | Bachelor of Science | 4 years |
| Business & Tourism | BSBA-FM | Business Administration, Financial Management | Bachelor of Science | 4 years |
| Business & Tourism | BSBA-MM | Business Administration, Marketing Management | Bachelor of Science | 4 years |
| Health | MID | Midwifery | `[confirm: Diploma or BS]` | `[confirm]` |
| Health | CGV | Caregiving | `[confirm: TESDA NC II certificate]` | `[confirm]` |

* **Academics page sections:** Hero → disciplines overview (five discipline rows, each with an image, blurb and program count) → academic calendar teaser → learning resources (library, labs) → faculty teaser → CTA.
* **Programs page sections:** Hero with search → sticky filter bar (discipline chips, degree type, duration) → program index (the same ruled rows as Home; list or grid toggle on desktop) → "Compare programs" drawer (up to 3 programs side by side) → FAQ accordion → CTA.
* **Program detail sections:** Breadcrumb → split hero (program name, degree, duration and "Apply to this program" on the left; photo on the right) → sticky in-page tabs (Overview, Curriculum, Requirements, Careers, Fees) → **Overview** (statement and key facts rule) → **Curriculum** (year-by-year accordion of subjects from `tblsubjects` with units in mono) → **Admission requirements** (checklist) → **Career opportunities** (a ruled list of roles) → **Tuition & fees** (link to the fee schedule) → related programs carousel → CTA.
* **Primary action:** Apply to this program. **Secondary actions:** Compare, Download prospectus, Talk to admissions.
* **Components:** Filter chips, search input, program rows, compare drawer, sticky tabs, accordion, checklist, fact rule.
* **Responsive:** Filters collapse into a "Filter" sheet on mobile, tabs become a horizontally scrolling strip, and the compare drawer becomes a full-screen sheet.
* **Relationships:** Admissions and Apply (the chosen program is pre-selected), Faculty (by department), Events (open house).

### 4.5 ADMISSIONS (`admissions.html`) and APPLY (`apply.html` → today `apply.php`, `portal/applicant/*`)

* **Purpose:** Make applying feel simple, guided and trustworthy.
* **Target users:** Freshmen, transferees, returning students, and parents helping them.
* **Admissions flow (the UI mirrors these steps everywhere):**
  1. **Explore programs** → 2. **Choose program** → 3. **Enrollment information** (who can apply, key dates, fees overview) → 4. **Enrollment form** → 5. **Requirements upload** → 6. **Entrance exam** (if the program requires one; schedule and results from `tblentrance_exams` and `tblexam_results`) → 7. **Payment** (`tbladmission_payments`, receipt in `tbladmission_receipts`) → 8. **Confirmation** (reference number, next steps, portal account).
* **Admissions page sections:** Hero ("Admissions", lede, "Start application") → **Process stepper** (an 8-step horizontal rail on desktop, vertical on mobile, each step with its description) → Applicant types (Freshman, Transferee, Returnee; a ruled list with requirements links) → Key dates agenda (`[Date]` placeholders) → Requirements checklist by applicant type → Tuition & scholarships → FAQ accordion → Contact admissions card → CTA.
* **Apply (multi-step form):**
  * The layout has a left step rail showing progress, a centre form card (720px max) and a right summary panel with the chosen program and a "Need help?" link.
  * Each step holds one topic and at most 8 fields. Steps are: Program choice → Personal information → Contact and address (province data in `assets/geo/ph-provinces.json`, `tblprovinces`) → Education background → Parent or guardian (`tblparents`) → Requirements upload (drag and drop with file chips and status) → Review → Submit.
  * Progress is autosaved, and an inline "Saved" status appears in mono.
  * Errors show inline, and an error summary appears at the top on submit.
  * After submission the applicant lands on the **Applicant dashboard** (a status timeline, exam schedule, payment due and a receipt download).
* **Primary action:** Start or continue the application. **Secondary actions:** Download requirements list, Contact admissions, Check status.
* **Responsive:** On mobile the step rail becomes a compact "Step 3 of 8" header with a progress bar, and a sticky bottom bar holds Back and Continue.
* **Relationships:** Programs, Cashier (admission payments), Registrar (applicant review), Login and the Applicant portal.

### 4.6 CAMPUS LIFE (`campus-life.html`)

* **Purpose:** Show the human experience: community, organizations, athletics, facilities and faith or values activities.
* **Target users:** Prospective students, parents, current students.
* **Sections:** Immersive hero (wide photo with the headline in its own zone, never overlapping the image) → Life in numbers (only verified figures, otherwise `[metric]`) → Organizations directory (filterable chips, ruled list) → Athletics (zig-zag with team photos) → Facilities carousel (the same component as Home) → Student services (guidance, health, library, IT support; ruled list with hours) → Photo gallery (a masonry grid that opens a lightbox; the existing lightbox pattern in `homepage_public.php` can be reused) → Student voices (quotes with portraits, placeholders only) → CTA.
* **Primary action:** Visit campus or Apply. **Secondary actions:** Explore organizations, View events.
* **Components:** Gallery lightbox (keyboard: arrows and Escape), quote block, chips, carousel.
* **Responsive:** The masonry grid becomes 2 columns and then 1, and the lightbox supports swipe.
* **Relationships:** Events, News, About.

### 4.7 ANNOUNCEMENTS (`announcements.html`)

* **Purpose:** Official, time-sensitive notices (enrollment, exams, schedules, suspensions, office hours).
* **Target users:** Everyone. Portal users also see notices targeted to their role (`tblannouncements.AUDIENCE`).
* **Sections:** Hero with search → Pinned or featured notice (lead layout: media, badge, title, summary) → Filter bar (category chips, audience, month) → Announcement feed (ruled rows with a mono date block, category badge, title and summary; a "New" badge for anything under 7 days old) → "Load more" → Subscribe to updates.
* **Announcement content model (for Announcements and News):** image or video, title, description or body, date, category, featured status, and **text above or below the media** (supported by `tblnews_media` block ordering: a Text block before or after an Image or Video block).
* **Primary action:** Read the notice. **Secondary actions:** Filter, Subscribe, Share.
* **Relationships:** Home (featured strip), portal dashboards (role-targeted notices), News.

### 4.8 NEWS (`news.html`) and ARTICLE (`article.html` → today `article.php`)

* **Purpose:** A digital-magazine newsroom that tells the institution's story.
* **Target users:** Community, alumni, parents, prospective students.
* **Newsroom compositions (mixed on one page):**
  * **Featured story:** A full-width 16:10 image, category and date, large serif title and dek.
  * **Two-column stories:** Two equal stories side by side (allowed as a pair, never as three).
  * **Large editorial story:** A split with the image on one side and a long excerpt on the other, placed on a white band.
  * **Small supporting stories:** Thumbnail plus title rows.
  * **Video feature:** A 16:9 poster with a play button that opens a modal player, plus copy.
  * **Feed:** A chronological list with category filters and "Load more".
* **Article template:** Breadcrumb → category kicker → H1 (Fraunces) → dek → byline (author, date, read time in mono) → hero media (image or video) → body at a 680px reading width with a 1.75 line-height. Media blocks can break out wider (up to 960px), captions are in mono, and text can sit above or below media as ordered in `tblnews_media`. Then pull quotes (Fraunces italic with a brass rule), share bar, related stories (a pair plus a list), and a newsletter CTA.
* **Primary action:** Read, then the next story. **Secondary actions:** Share, Subscribe, Filter by category.
* **Responsive:** Compositions collapse to one column, breakout media goes full bleed, and the share bar becomes a sticky bottom row.
* **Relationships:** Home, Announcements, Events, Campus Life.

### 4.9 EVENTS (`events.html`)

* **Purpose:** A clear, scannable calendar of school events.
* **Target users:** Students, parents, community.
* **Sections:** Hero → view switcher (Agenda | Month) → filter chips (Academic, Admissions, Athletics, Community, Holiday) → **Agenda view** (ruled rows grouped by month: large date, title, description, location and time, "Add to calendar") → **Month view** (a calendar grid with event dots, where clicking a day opens a side panel) → Featured event banner → Event detail modal or page (image, date, time, venue, description, map link, "Add to calendar" .ics, related events).
* **Primary action:** View event and add it to a calendar. **Secondary actions:** Filter, Subscribe to the calendar.
* **Responsive:** The month view is hidden on mobile (the agenda only), and filters become a scrolling chip strip.
* **Relationships:** Home, News, Admissions (open house, exams). Data: a new `tblevents` table is proposed (see Section 5).

### 4.10 FACULTY (`faculty.html`)

* **Purpose:** Show the people behind the teaching and build academic credibility.
* **Target users:** Prospective students and parents, current students.
* **Sections:** Hero → department filter (chips) and search → faculty grid (portrait, name, title, department, specialization; 4, then 3, then 2, then 1 columns) → profile drawer or page (bio, education, subjects taught from `tblteacher_subjects`, office hours, contact through the school only) → department heads highlight → CTA.
* **Primary action:** View profile. **Secondary actions:** Filter by department, Contact department.
* **Relationships:** Programs (by department), About.

### 4.11 CONTACT (`contact.html`)

* **Purpose:** Make it effortless to reach the right office.
* **Target users:** Everyone.
* **Sections:** Hero → office directory (a ruled list: Admissions, Registrar, Cashier, Guidance, Administration, each with phone, email, hours and a "Message" action; the email chooser modal pattern in `homepage_public.php` can be reused) → contact form (name, email, topic select, message; routed by topic) → map and directions (static map image, address, landmarks, commute notes) → social links → FAQ.
* **Primary action:** Send a message. **Secondary actions:** Call, Get directions.
* **Responsive:** The form comes first on mobile, and phone numbers are tap-to-call.
* **Relationships:** Admissions, the footer, every "Talk to admissions" link.

### 4.12 LOGIN (`login.html` → today `login.php`, `portals.php`)

* **Purpose:** One secure entry point that routes each role to its portal (`portal_home_for()` in `include/rbac.php`).
* **Layout:** A split screen. The left side is a brand panel (Night Ink, a serif statement, a campus photo). The right side is the sign-in card (username or email, password with a show toggle, "Remember me", "Forgot password?", primary "Sign in"). An inline error appears for bad credentials, and repeated failures trigger lockout messaging. A link reads "New applicant? Start your application".
* **Portal hub (`portals.php`):** A ruled list of role cards (Student, Teacher, Registrar, Cashier, Administrator, Applicant), each with a short description.

---

### 4.13 PORTAL ECOSYSTEM (architecture, design phase only)

**Shared portal shell:** A 248px left sidebar (monogram, role label, navigation groups, collapse toggle) → a top bar (page title, global search ⌘K, notifications bell with a count badge, profile menu) → content on Warm Paper with white panels. Portals use Geist and Geist Mono only, tables follow DESIGN.md §4, and loading and empty states follow DESIGN.md. On mobile, a bottom tab bar holds the top 4 destinations plus "More". Every portal dashboard opens with a greeting and today's date, a "Needs your attention" list, role KPIs (live numbers only), and role-targeted announcements.

#### STUDENT PORTAL (to be built at `portal/student/`; today it is a "Coming soon" page)
| Screen | Content | Data |
|---|---|---|
| Dashboard | Greeting, today's classes, latest grades, balance due, announcements, upcoming events, notifications | `tblclass_schedules`, `tblgrades`, `tblpayments`, `tblannouncements` |
| Profile | Personal info, contact, guardian, photo, change password | `tblstudent`, `tblparents` |
| Grades | By term: subject, units, grade, remarks; GPA; printable report | `tblgrades`, `tblsubjects` |
| Attendance | Calendar heatmap plus a list; present, late and absent counts per subject | `tblattendance`, `tblclass_attendance` |
| Schedule | Weekly timetable grid; list view on mobile | `tblclass_schedules`, `tblsections` |
| Announcements | Role-targeted feed | `tblannouncements` |
| School events | Agenda | events |
| Enrollment | Current enrollment status, subjects enrolled, section, school year, re-enrollment action | `tblenrollment`, `tblenrollment_details`, `tblschoolyear` |
| Payments | Assessment, payments made, balance, receipts download, pay online (future) | `tblpayments`, `tblfeeschedule` |
| Notifications | Inbox with read and unread states | `tblnotifications` |

#### TEACHER PORTAL (`portal/teacher/`)
| Screen | Content | Data |
|---|---|---|
| Dashboard | Today's classes, grades pending, attendance to take, announcements | `tblclass_schedules`, `tblteacher_subjects` |
| Classes | List of assigned classes, then a class detail view (roster, schedule, materials) | `tblsections`, `tblclass_schedules` |
| Students | Searchable roster across classes, then a student summary | `tblstudent`, `tblenrollment_details` |
| Attendance | Per-class daily sheet with present, late and absent toggles and bulk "all present"; QR scan mode (reusing `module/attendance/scan.php` with its sounds) | `tblclass_attendance` |
| Grades | Grade sheet per class and term: inline editable cells, validation, a draft then submit workflow | `tblgrades` |
| Subjects | Assigned subjects with descriptions and prerequisites | `tblsubjects`, `tblsubject_prerequisites` |
| Announcements | Post to your classes, view school notices | `tblannouncements` |

#### REGISTRAR PORTAL (`portal/registrar/`)
| Screen | Content | Data |
|---|---|---|
| Dashboard | Pending applications, enrollment counts by program, document requests queue | `tblapplicants`, `tblenrollment` |
| Student records | Search, filters, then a student record view (profile, academic history, documents) | `tblstudent`, `alumni_details` |
| Enrollment | Enrollment processing: assign section and subjects, prerequisite checks, confirm | `tblenrollment*`, `tblsubject_prerequisites` |
| Academic records | Transcript view, grade corrections log | `tblgrades`, `tblaudit_logs` |
| Student verification | Verify identity, documents and exam results for applicants | `tblapplicants`, `tblexam_results` |
| Documents | Requests queue (TOR, certificates) with statuses and uploads | `tbldocuments` |
| Reports | Enrollment reports, class lists, export to PDF or CSV | multiple |

#### CASHIER PORTAL (`portal/cashier/`)
| Screen | Content | Data |
|---|---|---|
| Dashboard | Collected today (live), pending payments, recent transactions | `tblpayments`, `tbladmission_payments` |
| Student payments | Search a student, then their assessment, then post a payment with an OR number | `tblpayments` |
| Enrollment fees | Fee schedule by program and year, fee types | `tblfeeschedule`, `tblfee_types` |
| Receipts | Issue, reprint and void (with reason) | `tbladmission_receipts` |
| Payment history | A filterable ledger | `tblpayments` |
| Balances | Outstanding balances with aging | computed |
| Reports | Daily collection, by fee type, export | multiple |

#### ADMINISTRATION (`module/*`, later restyled to the portal shell)
| Screen | Content | Data |
|---|---|---|
| Dashboard analytics | Enrollment trend, applications funnel, collections, active users (real data only) | multiple |
| Users | CRUD, reset password, activate or deactivate | `tblusers` |
| Roles | Role list | `tblusertype` |
| Permissions | A role × module matrix (extends `include/rbac.php`) | proposed |
| Announcements | Compose with audience targeting, schedule, pin | `tblannouncements` |
| Website content | Homepage sections (order, enable), content blocks, news editor with media blocks | `tblhomepage_*`, `tblnews*` |
| System settings | School info, school year, branding, email | `tblsettings`, `tblschoolyear` |
| Reports | Audit logs, exports | `tblaudit_logs` |

---

## 5. The Roadmap (Backlog)

*When `next-prompt.md` is complete, take the next task from here, top to bottom.*

### Phase A: Public website design in Stitch (High priority)
- [ ] **Home** (`index`): generate in Stitch from the baton, compare with the local draft, integrate. *(Current baton)*
- [ ] **Programs** (`programs`): discovery index with filters and compare.
- [ ] **Program detail** (`program-detail`): BSIT sample with tabs.
- [ ] **Admissions** (`admissions`): process stepper, requirements, dates.
- [ ] **Apply** (`apply`): multi-step form, step 2 (Personal information) as the representative screen.
- [ ] **Login** (`login`): split-screen sign-in.
- [ ] **Student dashboard** (`student-dashboard`): the portal shell reference screen.

### Phase A: continued (Medium priority)
- [ ] **About** (`about`)
- [ ] **News** (`news`), then **Article** (`article`)
- [ ] **Announcements** (`announcements`)
- [ ] **Events** (`events`)
- [ ] **Campus Life** (`campus-life`)
- [ ] **Faculty** (`faculty`)
- [ ] **Contact** (`contact`)
- [ ] **Academics** (`academics`)

### Phase B: Portal screens (Medium priority)
- [ ] Student: grades, schedule, payments, enrollment, attendance, profile, notifications
- [ ] Teacher: dashboard, attendance sheet, grade sheet, classes
- [ ] Registrar: dashboard, student record, enrollment processing, documents
- [ ] Cashier: dashboard, post payment, receipts, balances
- [ ] Admin: dashboard, users, permissions matrix, website content editor
- [ ] Mobile variants (`deviceType: MOBILE`) of Home, Programs, Apply and Student dashboard

### Phase C: Integration into PHP (Low priority here; each step needs explicit owner approval)
- [ ] Add `assets/bruce/tokens.css` and `assets/bruce/bruce.css` (the design system), without editing `public.css` or `custom.css`.
- [ ] Build new public templates (`theme/bruce_header.php`, `theme/bruce_footer.php`) alongside the existing ones.
- [ ] Port the Home design into a new `homepage_bruce.php` behind a switch, keeping `homepage_public.php` as a fallback.
- [ ] Seed the college programs (Section 4.4) into `tblcourses` and `tblmajors`.
- [ ] Propose a `tblevents` table and migration.
- [ ] Build `portal/student/` using the approved portal-shell design.
- [ ] Rebrand remaining text strings from St. Joseph to Bruce Class (content only).

### Low priority / enhancements
- [ ] Dark-mode variant of the design system (the existing site stores it under `sjcsTheme`).
- [ ] 404, 500 and maintenance ("coming soon") pages.
- [ ] Print styles for report cards and receipts.
- [ ] A global search overlay (⌘K).

---

## 6. Creative Freedom Guidelines

*Only use these once Section 5 Phase A is complete.*

1. **Stay on-brand:** Editorial, Institutional and Digitally fluent, following DESIGN.md exactly.
2. **Enhance the core:** Every new page must help someone learn about, join, or run school life at Bruce Class.
3. **Naming:** Lowercase, hyphenated filenames.
4. **No invented facts:** Use placeholders for any institution-specific number, date or name.

### Ideas to explore
*Pick one, build it, then REMOVE it from this list.*

- [ ] `scholarships.html`: Scholarship types, eligibility checker, application steps
- [ ] `tuition.html`: Tuition and fee explorer by program and year
- [ ] `alumni.html`: Alumni stories and network (`alumni_details`)
- [ ] `library.html`: Library services, hours, e-resources
- [ ] `calendar-academic.html`: Academic calendar (terms, exams, holidays)
- [ ] `virtual-tour.html`: A photo-led campus tour with hotspots

---

## 7. Rules of Engagement

1. **Do not start the loop** until the owner explicitly asks. Then run one iteration at a time unless told otherwise.
2. Do not recreate a page already checked in Section 4.0.
3. Every Stitch prompt must include the DESIGN.md Section 6 block.
4. Always update `.stitch/next-prompt.md` before finishing an iteration, and persist `.stitch/metadata.json` after any Stitch project or screen change.
5. **Never modify existing PHP, SQL, CSS or JS application files during the design loop.** Loop output goes only to `.stitch/` and `site/public/`. PHP integration is Phase C and needs explicit approval.
6. Never invent statistics, dates, names or accreditation. Use visible placeholders.
7. Keep links relative inside `site/public/` and wire every placeholder `href="#"` when its target page exists.
8. Check each page on mobile (390px) and desktop (1440px): no horizontal scroll, visible focus states, 44px targets.
