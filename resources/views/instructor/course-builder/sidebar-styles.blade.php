@push('styles')
<style>
    /* Course builder shell — shared across edit course, lessons, quizzes */
    .cb-wrap {
        display: flex;
        align-items: stretch;
        min-height: min(78vh, 900px);
        overflow-x: hidden;
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);
    }
    .cb-sidebar {
        width: 360px;
        min-width: 360px;
        max-width: 360px;
        flex-shrink: 0;
        align-self: stretch;
        padding: 0;
        overflow: hidden;
        border-right: 1px solid #e2e8f0;
        border-radius: 1rem 0 0 1rem;
        background: #fafbfc;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    .cb-sidebar-panel {
        display: flex;
        flex-direction: column;
        flex: 1 1 0;
        min-height: 0;
        padding: 1rem;
    }
    .cb-sidebar-card {
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .cb-sidebar-card__head {
        flex-shrink: 0;
        padding: 1.1rem 1.1rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
    }
    .cb-sidebar-card__head .cb-sidebar-label {
        margin-bottom: 0.5rem;
    }
    .cb-sidebar-card__body {
        flex: 1 1 0;
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        overscroll-behavior: contain;
        padding: 0.85rem 1rem 1rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-gutter: stable;
    }
    .cb-sidebar-card__body::-webkit-scrollbar {
        width: 8px;
    }
    .cb-sidebar-card__body::-webkit-scrollbar-track {
        background: transparent;
    }
    .cb-sidebar-card__body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .cb-sidebar-card__body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .cb-sidebar-card__foot {
        flex-shrink: 0;
        padding: 1rem 1.1rem 1.1rem;
        border-top: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }
    .cb-sidebar-foot-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.6rem;
    }
    .cb-sidebar-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    .cb-course-select {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.55rem 0.85rem;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 0.65rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .cb-course-select:hover {
        border-color: #94a3b8;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
    }
    .cb-course-select svg { flex-shrink: 0; color: #64748b; }
    .cb-btn-add-module {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-top: 0.55rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #0f172a;
        background: #fff;
        border: 1px dashed #94a3b8;
        border-radius: 0.65rem;
        transition: background 0.15s, border-color 0.15s;
    }
    .cb-btn-add-module:hover {
        background: #e2e8f0;
        border-color: #64748b;
        color: #0f172a;
    }
    .cb-tree { margin-top: 0; }
    .cb-module-block {
        padding: 0.35rem 0 0.85rem;
        margin-bottom: 0.25rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .cb-module-block:has(+ .cb-ungrouped) {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0.5rem;
    }
    .cb-module-header {
        display: flex;
        align-items: flex-start;
        gap: 0.45rem;
        padding: 0 0 0.35rem 0.15rem;
        margin-bottom: 0.25rem;
    }
    .cb-module-header .cb-drag { margin-top: 0.2rem; }
    .cb-module-title {
        font-size: 0.8125rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
    }
    .cb-lessons { margin-left: 0; padding-left: 0.25rem; }
    .cb-drag {
        color: #cbd5e1;
        font-size: 0.7rem;
        letter-spacing: -0.05em;
        cursor: grab;
        pointer-events: none;
        user-select: none;
        flex-shrink: 0;
    }
    .cb-item {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.5rem;
        margin: 0.2rem 0;
        border-radius: 0.5rem;
        text-decoration: none;
        color: #334155;
        font-size: 0.8125rem;
        font-weight: 500;
        border-left: 3px solid transparent;
        transition: background 0.12s, color 0.12s;
    }
    .cb-item:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .cb-item.active {
        background: #0f172a;
        color: #fff;
        border-left-color: #38bdf8;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
    }
    .cb-item.active .cb-drag { color: #64748b; }
    .cb-item.active svg { stroke: #fff; }
    .cb-item-icon-wrap {
        flex-shrink: 0;
        width: 26px;
        height: 26px;
        border-radius: 0.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e2e8f0;
    }
    .cb-item.active .cb-item-icon-wrap {
        background: rgba(255, 255, 255, 0.15);
    }
    .cb-item-icon-wrap--lesson { background: #dbeafe; color: #1d4ed8; }
    .cb-item-icon-wrap--quiz { background: #fef3c7; color: #b45309; }
    .cb-item-icon-wrap--assignment { background: #fce7f3; color: #9d174d; }
    .cb-item.active .cb-item-icon-wrap--lesson,
    .cb-item.active .cb-item-icon-wrap--quiz,
    .cb-item.active .cb-item-icon-wrap--assignment {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }
    .cb-item-icon-wrap svg { stroke: currentColor; }
    .cb-label {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cb-lesson-quiz-dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        background: #22c55e;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.18);
        flex-shrink: 0;
        align-self: center;
    }
    .cb-item.active .cb-lesson-quiz-dot {
        background: #38bdf8;
        box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.28);
    }
    .cb-section-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.35rem;
        padding-left: 0.15rem;
    }
    .cb-ungrouped {
        margin-top: 0.35rem;
        padding-top: 0.65rem;
        border-top: 1px dashed #e2e8f0;
    }
    .cb-actions {
        margin-top: 0;
        padding-top: 0;
        border-top: none;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }
    .cb-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 0.6rem;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #0f172a;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
    }
    .cb-action-btn:hover {
        background: #0f172a;
        border-color: #0f172a;
        color: #fff;
    }
    .cb-action-btn svg { flex-shrink: 0; opacity: 0.85; }
    .cb-action-btn:hover svg { opacity: 1; }
    .cb-main {
        flex: 1;
        min-width: 0;
        min-height: 0;
        align-self: stretch;
        display: flex;
        flex-direction: column;
        padding: 1.5rem 2rem 2rem;
        overflow: auto;
        background: #fafbfc;
    }
    @media (max-width: 991.98px) {
        .cb-wrap { flex-direction: column; }
        .cb-sidebar {
            width: 100%;
            max-width: none;
            min-width: 0;
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            max-height: 50vh;
            border-radius: 1rem 1rem 0 0;
        }
    }
</style>
@endpush
