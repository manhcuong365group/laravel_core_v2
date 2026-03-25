#!/usr/bin/env node

const fs = require("fs-extra")
const dayjs = require("dayjs")

const BASE = ".ai"

function ensureDir(path) {
    fs.ensureDirSync(path)
}

function read(file) {
    if (!fs.existsSync(file)) return ""
    return fs.readFileSync(file, "utf-8")
}

function write(file, content) {
    fs.writeFileSync(file, content)
}

function init() {
    ensureDir(`${BASE}/core`)
    ensureDir(`${BASE}/memory/logs`)
    ensureDir(`${BASE}/runtime`)
    ensureDir(`${BASE}/agents`)
    ensureDir(`${BASE}/prompts`)

    if (!fs.existsSync(`${BASE}/core/SOUL.md`)) {
        write(`${BASE}/core/SOUL.md`, `# SOUL

- Builder
- Ngắn gọn
- Rõ ràng
- Ship trước, tối ưu sau
- Không chắc thì nói không chắc
`)
    }

    if (!fs.existsSync(`${BASE}/core/USER.md`)) {
        write(`${BASE}/core/USER.md`, `# USER

- Timezone: Asia/Ho_Chi_Minh
- Code: English
- Chat: Vietnamese
- Thích câu trả lời ngắn
`)
    }

    if (!fs.existsSync(`${BASE}/core/MEMORY.md`)) {
        write(`${BASE}/core/MEMORY.md`, `# MEMORY

## Projects

## Decisions

## Lessons Learned
`)
    }

    if (!fs.existsSync(`${BASE}/core/RULES.md`)) {
        write(`${BASE}/core/RULES.md`, `# RULES

1. Read SOUL.md
2. Read USER.md
3. Read MEMORY.md
4. Read latest daily log
5. Do the task
6. Write log after finishing
`)
    }

    if (!fs.existsSync(`${BASE}/runtime/tasks.json`)) {
        write(`${BASE}/runtime/tasks.json`, JSON.stringify({ tasks: [] }, null, 2))
    }

    console.log("✅ Initialized .ai workspace")
}

function log(message) {
    if (!message) {
        console.log('❌ Thiếu nội dung log. Ví dụ: node ai.js log "fixed auth bug"')
        return
    }

    const date = dayjs().format("YYYY-MM-DD")
    const file = `${BASE}/memory/logs/${date}.md`

    let content = read(file)
    if (!content.trim()) {
        content = `# ${date}\n\n## Logs\n`
    }

    content += `- ${message}\n`
    write(file, content)

    console.log("🧾 Logged:", message)
}

function summarize() {
    const logDir = `${BASE}/memory/logs`
    const memoryFile = `${BASE}/core/MEMORY.md`

    if (!fs.existsSync(logDir)) {
        console.log("❌ Chưa có thư mục logs. Hãy chạy: node ai.js init")
        return
    }

    const files = fs.readdirSync(logDir).filter(f => f.endsWith(".md"))
    if (files.length === 0) {
        console.log("❌ Chưa có log nào để summarize")
        return
    }

    let summary = `\n\n## Summary ${dayjs().format("YYYY-MM-DD")}\n`

    for (const f of files) {
        const data = read(`${logDir}/${f}`)

        if (/supabase/i.test(data)) summary += "- Using Supabase\n"
        if (/firebase/i.test(data)) summary += "- Avoid Firebase in current setup\n"
        if (/bug|error|issue/i.test(data)) summary += "- Found system issues that need review\n"
        if (/auth/i.test(data)) summary += "- Working on authentication flow\n"
        if (/agent/i.test(data)) summary += "- Improving multi-agent workflow\n"
    }

    fs.appendFileSync(memoryFile, summary)
    console.log("🧠 Summarized into .ai/core/MEMORY.md")
}

const cmd = process.argv[2]
const arg = process.argv.slice(3).join(" ")

if (cmd === "init") init()
else if (cmd === "log") log(arg)
else if (cmd === "summarize") summarize()
else {
    console.log(`
Usage:
  node ai.js init
  node ai.js log "message"
  node ai.js summarize
`)
}