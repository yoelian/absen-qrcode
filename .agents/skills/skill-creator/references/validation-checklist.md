# Skill Quality & Validation Checklist

Before finalizing and shipping any new Antigravity skill, verify every item below:

---

## 1. Frontmatter Quality
- [ ] `name` is lowercase kebab-case (e.g. `senior-developer`, `anti-slop`).
- [ ] `description` clearly explains **WHAT** the skill does AND **WHEN** to activate it.
- [ ] Description contains strong trigger keywords that match realistic user prompts.
- [ ] No conversational filler in description; formatted cleanly as third-person imperative.

## 2. Token Efficiency (Progressive Disclosure)
- [ ] Main `SKILL.md` is concise (under ~12 KB / 300 lines).
- [ ] Heavy reference documentation, schemas, or large code snippets are moved to `references/`.
- [ ] All cross-references use relative Markdown links (e.g. `[Checklist](./references/checklist.md)`).

## 3. Actionability & Runbooks
- [ ] Instructions are concrete and step-by-step, not vague or purely theoretical.
- [ ] Includes clear verification steps (e.g. run test suite, check logs, inspect diff).
- [ ] Avoids lecturing the model on general knowledge it already possesses.

## 4. Location & Deployment
- [ ] Deployed to Global (`~/.gemini/config/skills/<name>/`) if applicable across projects.
- [ ] Deployed to Workspace (`.agents/skills/<name>/`) if project-specific or tracked in Git.
- [ ] Directory name matches the frontmatter `name` exactly.
