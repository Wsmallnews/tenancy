# 首页改造任务书（AI 执行版）

> 本文档由 `tasks/首页改造计划.md` 整理而成，含用户已确认的决策（§8）与 MasterGo 设计稿实测规格（§2）。
> 设计稿已可读取（MCP 权限已修复），**无阻塞项，可开工**。

---

## 1. 任务摘要

按设计稿重做 CMS 前台首页（`http://tenancyv4.test/cms/first`）的视觉与布局：

- 重做范围：**页面头部、页脚、首页 6 个内容区块、人才储备列表页单项样式**
- 不做：数据结构变更、后台功能、导航栏组件本身、内容区 slot 机制
- 原则：**以改样式为主**，数据字段沿用现有组件；优先复用 `wsmallnews/support` 的 `sn-*` 工具类
- 还原标准：层级与间距按设计稿 1:1（设计稿数值见 §2，1440 设计稿像素可直接用 Tailwind 任意值或对应默认刻度）

---

## 2. 设计稿规格（MasterGo DSL 实测，fileId=202418967932518 / layerId=5:254）

### 2.0 画布与栅格

- 画布 1440×2543；**页面底色 `#F0F4F8`**（浅蓝灰）；内容区宽 **1232px** 居中（页边距 104）
- 字体 Noto Sans SC；区块之间垂直间距约 **32px**
- 卡片通用：白底 + 圆角 12~16px + 阴影 `0 4px 6px -4px rgba(0,0,0,.1), 0 10px 15px -3px rgba(0,0,0,.1)`（可用 `sn-container` 基础上微调圆角）

### 2.1 设计色板 → 项目映射

| 设计值 | 用途 | 映射 |
|---|---|---|
| `#1A5FB4` | 主题蓝（导航/按钮/数字/激活态） | `primary-600`（全部主题蓝一律映射 `primary-*`） |
| `linear-gradient(180deg,#1A5FB4,#3B82F6)` | 区块标题竖条 | primary 渐变 |
| `rgba(26,95,180,0.1)` | 图标底/标签底 | `primary-100` 系 |
| `rgba(232,240,254,0.3)` | 列表行悬停底 | primary-50/100 半透明 |
| `#1A1A2E` | 标题深黑 | gray-900 系 |
| `#3D3D3D` | 次深灰 | gray-700 系 |
| `#718096` | 正文灰 | gray-500 系 |
| `#E2E8F0` | 边框/分隔线 | gray-200 系 |
| `#E74C3C` | 统计趋势红（↑较上月） | red/danger 系 |
| `#10B981` `#F59E0B` `#F43F5E` | 服务案例分类 pill、科研成果类型标签的强调色（非主题色，保留 Tailwind 原生 emerald/amber/rose 语义色） | 保留 |
| `#ECFDF5`/`#059669` | 研究论文标签浅绿底/深绿字 | emerald-50/emerald-600 |

### 2.2 页面纵向骨架（页绝对 y 坐标，1440 宽）

| y | 高 | 区块 |
|---|---|---|
| 0 | 41 | 顶部工具条 |
| 41 | 96 | 页头（logo+站名+搜索+登录/注册） |
| 137 | 48 | 导航条（**不改**，样式参考：主题蓝底、白字、10 项等宽 123.2） |
| 185 | 484 | 首屏：左轮播 616×420 + 右最新动态卡 616×420（内容区起 y217，即上 padding 32） |
| 669 | 120 | 统计 4 卡（290×120，gap 24） |
| 821 | 52 | 人才储备区块头 |
| 905 | 213 | 人才 4 卡（290×213，gap 24） |
| 1143 | 52 | 服务案例区块头 |
| 1227 | 314 | 服务案例 4 卡（290×313.75，gap 24） |
| 1579.75 | 52 | 科研成果区块头 |
| 1663.75 | 496 | 科研成果 3 行 × 每行 2 卡（606×152，行间 20） |
| 2191.75 | 351 | 页脚（白底，内容区 y2240.75 高 206） |
| 2494.75 | 48 | 底部条（主题蓝底） |

### 2.3 各区块实测细节

**A. 顶部工具条（41px，白底，底边框 `#E2E8F0`）**
- 内容器 1280 宽（px-24），两端对齐
- 左：`欢迎访问全国农作物种质资源信息平台` 12px `#718096`
- 右：`国家农业科学数据中心农业生物种质资源专题平台` | `国家科技资源共享服务平台`，12px `#718096`，竖线分隔（`#E2E8F0`），gap 16

**B. 页头（96px，白底，轻阴影）**
- 左：圆形 logo 图标 56×56（主题蓝底+阴影，内 24px 白色图标）+ 站名两行：中文 20px Bold `#1A1A2E`；英文 12px `#718096` 字距 0.6；logo 与文字 gap 16，整体 px-24
- 右侧组（gap 16）：
  - 搜索框 348×44：边框 2px `#E2E8F0` 圆角 8；内左侧输入区（placeholder `请输入关键词` 14px `#9CA3AF`），右端主色按钮 88×40 圆角 8（白字 `搜索` 14px Medium + 14px 搜索图标）
  - 登录按钮 72×44：边框 2px `#E2E8F0` 圆角 8，文字 14px Medium `#4A5568`
  - 注册按钮 68×40：主题蓝填充圆角 8，白字 14px Medium

**C. 首屏左：轮播图（616×420）**
- 背景图满铺 + 深色遮罩（自定深色渐变即可）
- 文案区 padding 32、底部对齐、竖向 gap 8：大标题 30px Bold 白（示例：`保护种质资源 赋能农业创新`）；副标题 14px 白/80（`整合共享 · 开放创新 · 支撑育种 · 服务产业`）
- 4 个玻璃标签（权威数据/标准规范/安全可靠/开放共享）：12px Medium 白字、圆角 9999、底 `rgba(255,255,255,0.2)` + backdrop-blur(8) + 边框 1px `rgba(255,255,255,0.3)`、padding 8px16px、间距 12
  - ⚠️ 设计稿中标签(y338)与标题(y324)重叠，属于设计稿错误：**实现为标签行在大标题上方**
- 轮播指示点（右下 y394）：激活 24×10 主题蓝圆条 + 2 个 10×10 `rgba(255,255,255,0.5)` 圆点，间距 8（swiper 组件自带指示器样式尽量贴近）

**D. 首屏右：最新动态卡（616×420，白底，圆角 0 16 16 0，阴影，padding 24）**
- 头行：渐变竖条 4×32 圆角 2 + 标题 `最新动态` 18px Bold `#1A1A2E`；右端 `更多` 12px `#718096` + 6×10 箭头
  - ⚠️ **按用户要求：此大标题整行去掉**（轮播与列表拆分后此卡无大标题）
- 分类 tab 行（原小标题）：激活态 14px SemiBold `#1A5FB4` + 底部 56×2 主题蓝下划线；非激活 14px Regular `#718096`；tab 间距 24；整行底部 1px `#E2E8F0` 分隔线（下划线压在分隔线上）
- 列表 4 行：每行高 88、圆角 12、padding 12、flex row gap 16；悬停底 `rgba(232,240,254,0.3)`；行间距 4
  - 行内：缩略图 80×64 圆角 8；右侧标题 14px/500 `#1A1A2E` 单行截断 + 日期 12px `#718096`（`Y-m-d`）
- **数据**：tab = 文章分类（树形查询，最多 5 个，默认选中第一个，点击仅刷新列表）；列表 = 当前选中分类的文章

**E. 统计 4 卡（290×120，圆角 16，白底阴影，padding 24，flex row 居中 gap 20）**
- 左：图标容器 56×56 圆角 12 底 `rgba(26,95,180,0.1)`，内 primary 色图标（约 21×24）
- 右列：标签 12px `#718096`；数值 24px Bold `#1A5FB4`（千分位）；趋势行 12px `#E74C3C` + 上箭头 7.5×10（示例 `较上月 3.2%`）
- **数据映射**：现有 Overview 四项（新品种/种质资源/科研专家/科研成果）；趋势行无数据来源，可先不渲染或做占位

**F. 区块头（52px，人才储备/服务案例/科研成果同款）**
- 左：渐变竖条 4×32 + 标题（约 20px Bold，行高 28）+ 副标题 14px `#718096`（两行，间距 4）
- 右：`查看更多` + 7.5×12 右箭头（文字约 14px `#718096`）——**注意垂直居中对齐（设计稿此处未对齐，属可修正错误）**

**G. 人才储备卡（290×213，圆角 12，白底阴影）⚠️ 与设计稿不同，按用户指定**
- 设计稿原始做法（上部 149 = 左 64px 宽主题蓝竖块写职级 + 右侧信息；下部 64 = 查看详情按钮 248×44 边框 2px 主题蓝圆角 8、文字 14px Medium 主题蓝）
- **用户指定内容区改用参考图** `tasks/images/人才储备参考图.png`：上部内容区 = 左**圆形头像** + 右侧字段竖排（名称 18px Bold `#1A1A2E`、研究标签、研究方向、成果，12px `#718096`）
- **查看详情按钮按设计稿**（描边 2px primary、圆角 8、14px Medium primary，宽约 248 居中）
- 其余细节（职级标签：10px Medium primary 底 `rgba(26,95,180,0.1)` 圆角 4）可沿用设计稿

**H. 服务案例卡（290×313.75，圆角 12，白底阴影）×4，gap 24**
- 顶部图 290×176 满铺（圆角随卡片）
- 分类 pill 左上 (12,12)：72×24 圆角 9999，白字 12px Medium；四色：种质创新=主题蓝→primary、资源评价=`#10B981`、技术服务=`#F59E0B`、平台支撑=`#F43F5E`
- 正文区 padding 0 20：标题 14px Bold `#1A1A2E`（y189）；描述 12px `#718096` 两行（y219.75）；`合作单位：xxx` 12px `#718096`（y263.75）
- **数据**：全部已发布文章（分类 pill 用文章第一个分类）

**I. 科研成果（3 行 × 2 卡，卡 606×152，圆角 12，白底阴影，padding 20，行间 20；每行第 2 卡底色 `rgba(232,240,254,0.2)`）**
- 卡内：左图 144×112 圆角 12；右侧（gap 20）：
  - 标题行：标题 14px Bold `#1A1A2E` + 类型标签（56×19 圆角 4，10px Medium：研究论文=emerald 底浅绿/深绿字、技术报告=primary 浅底/主题蓝字、成果转化、专利成果同款四色系）gap 8
  - 元信息 3 行 12px `#718096` 间距 4：作者 / 期刊或类别 / 时间（示例 `作 者：李建国 等`、`期 刊：…`、`发表时间：2024-06-01`）
- **数据映射**：复用现有 ScientificResearch 查询（文章）；标题=文章标题、图=post_image、标签=文章分类、元信息=作者(如有)/描述/日期，按现有字段尽量贴近

**J. 页脚（白底，内容 1232×206）**
- 列 1（389 宽）：圆形 logo 图标 48×48 主题蓝 + 平台名 16px Bold `#1A1A2E`；`主办单位：…`、`承办单位：…` 14px `#718096`（间距 8/28）；`© 2024 All Rights Reserved` 12px
- 列 2 平台导航 / 列 3 服务支持（各 178.66）：标题 14px Bold `#1A1A2E`，链接 12~14px `#718096` 竖排
- 列 4 联系我们：地址（可两行）/电话/邮箱，14px `#718096`，前置 9~12px primary 小图标，行间 ~10
- 列 5 关注公众号：二维码方块 + 标题
- **数据**：导航列用现有 footer 的 `$navigations`，联系方式用 GeneralSettings（phone/email/address/wechat_official_qrcode/copyright）
- **底部条（48px 主题蓝底）**：12px 白字居中，`国家农业科学数据中心 | 国家科技资源共享服务平台 | 农业农村部种质资源平台 | 鲁ICP备05002188号-1 | 鲁公网安备…`，分隔竖线 `rgba(255,255,255,0.4)`，间距 24（备案号字段：GeneralSettings `beian_no`）

### 2.4 实现期待办（写码时补拉 DSL）

本次为任务书提取了 11 个关键分区（0/1/12/13/14/19/24/26/29/32/34）；**写码时需按 MasterGo 工作流补拉全部 35 个分区**（导航项 2-11、统计卡 15-17、区头 18/23/25、人才卡 20-22、科研成果行 27-28、页脚列 30-31/33 均为已采样分区的克隆变体），并按 `@@SVG:{svgShortKey}@@` 占位 → `mcp__applyDesign` 注入真实图标 SVG 的流程执行。

---

## 3. 现状梳理（渲染链路与关键文件）

### 3.1 页面容器链路

```
config/sn-cms.php
  └─ themes.page_container => 'sn-cms::container.page'   ← 本次要替换的自定义入口
       ├─ 页面头部（banner 图 + logo + 登录/注册）        ← 重做（按 §2.3-A/B）
       ├─ livewire:sn-cms::components.navigation.navigation（导航） ← 不动
       ├─ {{ $slot }}（内容区域，首页内容在这里渲染）      ← 不动机制，改内容
       └─ livewire:sn-cms::components.footer（页脚）      ← 重做（全新组件，按 §2.3-J）
```

自定义方式：在 `config/sn-cms.php` 中把 `themes.page_container` 指向 app 层新建的容器组件（参考现有 `vendor/wsmallnews/cms/resources/views/components/container/page.blade.php` 的写法）。

### 3.2 首页组件与注册别名（注册处在 `app/Providers/AppServiceProvider.php`）

| 首页区块 | Livewire 别名 | 类 | 视图 |
|---|---|---|---|
| 首屏轮播+文章列表 | （新建，如 `sn-components-index-featured`） | 新建 | 新建 |
| 统计区域 | `sn-components-index-overview` | `App\Livewire\Components\Index\Overview` | `resources/views/livewire/components/index/overview.blade.php` |
| 人才储备 | `sn-components-index-personnels` | `App\Livewire\Components\Index\Personnels` | `.../index/personnels.blade.php` |
| 服务案例 | `sn-components-index-posts` | `App\Livewire\Components\Index\Posts` | `.../index/posts.blade.php` |
| 科研成果 | `sn-components-index-scientific-research` | `App\Livewire\Components\Index\ScientificResearch` | `.../index/scientific-research.blade.php` |
| 首页入口 | — | `App\Livewire\Index` | `resources/views/livewire/index.blade.php` |

### 3.3 可参考的扩展包组件

- **首屏参考组件**：`vendor/wsmallnews/cms/src/Livewire/Components/Post/IndexPosts.php` + 视图 `.../livewire/tradition/components/post/index-posts.blade.php`
  - 轮播图用法：`<x-sn-support::swiper :slides="$slides" :has-thumb="false" />`，slides 结构 `['image' => ..., 'label' => ..., 'url' => ...]`
- **分类查询参考**：`vendor/wsmallnews/category/src/Livewire/Components/Categories.php`（见 §6.1）
- **样式工具类**：`vendor/wsmallnews/support/resources/css/index.css`（`sn-container`、`sn-primary-bg`、`sn-h1-text`、`sn-btn-*`、`sn-badge-*`、`sn-image-placeholder`、`sn-link-more` 等），**优先使用**，圆角/间距不足处用 Tailwind 原子类补

### 3.4 数据字段速查

- **Post（文章）**：`title`、`description`、`post_image`（媒体）、`categories()`（多对多，wsmallnews/category）、`flags`（json 数组，含 `top`=置顶，查询用 `scopeHasFlag('top')`）、`published_at`、`order_column`
- **Personnel（人员）**：`name`、`professional_title`（职称）、`qualification`（学历）、`research_focus`（研究方向）、`research_result`（研究成果）、`avatar`（媒体）；查询 `scopeTenant()->normal()->display()`
- **文章分类（Category）**：树形表（nested set，`sn_categories`，字段含 `_lft/_rgt/parent_id/type_id`）；**后台已限制只有一级**；文章分类类型为 `sn-cms:0` 对应的 CategoryType（表 `sn_category_types`，name="Sn-Cms"）

---

## 4. 响应式与布局规范（全站通用）

- 断点使用 Tailwind：`md`（≥768px）、`lg`（≥1024px）
- **区块级栅格用 grid，卡片内部内容用 flex**
- 各区块每行卡片数：

| 区块 | < md | ≥ md | ≥ lg |
|---|---|---|---|
| 统计 / 人才储备 / 服务案例 | 1 | 2 | 4（设计稿：卡宽 290、gap 24） |
| 科学研究 | 1 | 2 | 3 |
| 首屏（轮播+列表） | 竖排：上轮播、下列表 | 同左 | 横排：左右各 50%，中间 gap（用户指定要加 gap） |

- 需在 PC、平板、移动端均可正常显示，不允许出现横向滚动

---

## 5. 页面头部与页脚（自定义 page_container）

1. 新建 app 层页面容器组件（blade component），替换 `sn-cms::container.page`：
   - **页面头部**：按 §2.3-A/B 实现（顶工具条 + 页头两行）
   - **导航**：继续使用 `<livewire:sn-cms::components.navigation.navigation ...>`，不改动
   - **内容区域**：保留 `{{ $slot }}`
   - **页脚**：见下
2. **页脚**：**写一个全新组件**（app 层新建，不改 vendor、不覆盖旧视图），按 §2.3-J 实现；数据结构沿用现有 footer 组件输出（`$navigations` 导航树 + `$general` 站点设置）
3. 修改 `config/sn-cms.php`：`themes.page_container` 指向新容器组件

---

## 6. 首页内容区块需求

### 6.1 首屏：轮播图 + 文章列表（新建组件）

**数据（两侧完全独立）：**

- **左侧轮播图** = **置顶文章**：`flags` 含 `top` 的已发布文章，按 `order_column` desc、`id` desc，**最多 10 条**；样式按 §2.3-C（背景图+遮罩+文案+玻璃标签；swiper 复用 `x-sn-support::swiper`，文案叠加层自定义）
- **右侧文章列表** = **按分类查询**，样式按 §2.3-D：
  - 分类 tab：**按树形分类查询方法查询**（参考 `vendor/wsmallnews/category/src/Livewire/Components/Categories.php::getNestedset()`）：

    ```php
    // Categoryable 机制：CategoryType 按 scope 解析，文章分类 scope 为 sn-cms
    // 等价于 Categories.php 中的: $this->getScopedQuery()->normal()->defaultOrder()->get()->toTree()
    $categoryType = CategoryType::scopeable(['scope_type' => 'sn-cms', 'scope_id' => 0])->firstOrFail();
    $categories = Category::scoped([...$this->getScopeable(), 'type_id' => $categoryType->id])
        ->normal()->defaultOrder()->get()->toTree();   // 只有一级，toTree 后即扁平的一级分类列表，已按排序字段排好
    ```

  - **最多展示 5 个分类**（取排序后的前 5）
  - **默认选中第一个分类**，展示该分类下的文章
  - **点击分类只刷新右侧文章列表**（Livewire 重新查询），左侧轮播图不受影响
- 文章列表行：缩略图 80×64 圆角 8 + 标题 14px 单行截断 + 日期，行高 88、悬停浅蓝底（§2.3-D）

**布局：**

- 外层 flex：`≥ lg` 左右两栏各占 50%（`lg:flex-row` + gap）；`< lg` 上下堆叠（`flex-col`），上面轮播图、下面文章列表
- 右侧列表**无大标题**（去掉"最新动态"头行），首行即分类 tab

### 6.2 统计区域（改 `index/overview.blade.php`）

- 数据与字段不变（新品种 / 种质资源 / 科研专家 / 科研成果），样式按 §2.3-E；栅格 1 / 2 / 4

### 6.3 人才储备（改 `index/personnels.blade.php`）

- 区块头按 §2.3-F；卡片按 §2.3-G（**内容区用参考图：圆形头像+右侧字段竖排；查看详情按钮用设计稿：描边 2px primary 圆角 8**）
- 栅格 1 / 2 / 4；整卡可点击跳转详情页（`Utils::route('personnels.show', $personnel->id)`）

### 6.4 服务案例（改 `index/posts.blade.php`）

- 区块头按 §2.3-F；卡片按 §2.3-H；栅格 1 / 2 / 4
- **查询保持现状：查所有已发布文章**（用户后续会自行改为按分类过滤，不要动查询逻辑）

### 6.5 科学研究（改 `index/scientific-research.blade.php`）

- 区块头按 §2.3-F（修正"查看更多"与箭头的垂直对齐）；卡片按 §2.3-I（lg 3 列，行内第 2 卡浅蓝底）
- 注意：设计稿是 2 列×3 行（每行 2 卡），但响应式规则要求 **lg 3 列**（§4），以 §4 为准

### 6.6 人才储备列表页样式同步（`resources/views/livewire/components/personnels.blade.php`）

首页人才储备卡片完成后，把**列表页的单个人员卡片**样式改成与首页单项一致的样式（搜索框、分页等其余部分不动）。

---

## 7. 样式与实现约束

1. **样式完全参考设计稿**（§2 规格已实测提取），除上文明确指出的差异点与设计稿错误
2. **主题色：设计稿主题蓝 `#1A5FB4` 一律映射到现有 `primary-*` 色板**；非主题的强调色（emerald/amber/rose 等）按 §2.1 保留
3. 优先使用 `wsmallnews/support` 的 `sn-*` 工具类，其次 Tailwind 原子类
4. 暗黑模式：跟随现有 `sn-*` 类的 dark 变体习惯，新写的样式需带 `dark:` 变体
5. PHP 改动后运行 `vendor/bin/pint --dirty`；前端类名变更后需重新构建（Vite dev 未运行时跑 `npm run build`）
6. 不修改 `vendor/` 下任何文件
7. 图标：设计稿 PATH 图标用 MasterGo 流程（`@@SVG:{svgShortKey}@@` 占位 + `mcp__applyDesign` 注入）；若脱离 MasterGo 流程手写，可用 Heroicons 就近替代

---

## 8. 已确认决策（用户答复回填）

| # | 问题 | 决策 |
|---|---|---|
| 1 | 文章分类查询方式 | 树形查询方法（参考 `Categories.php::getNestedset()`），后台已限制只有一级，排序自动应用 |
| 2 | 主题色 | 一律映射到 `primary-*` |
| 3 | 首屏分类 tab 交互 | 点击分类**只刷新右侧文章列表**；左侧轮播图与右侧列表**完全不相关**；轮播图=置顶文章最多 10 条；右侧**默认选中第一个分类** |
| 4 | 服务案例数据 | 暂时查所有文章，用户想好后自行修改 |
| 5 | 页脚实现 | 通过 `cms-overrides` 视图覆盖实现（见 §3.0，等价于"全新组件"且全站生效） |
| 6 | 页面头部内容 | 按设计稿来（§2.3-A/B） |
| 7 | 设计稿获取 | MasterGo MCP 已可用；任务书已提取关键分区规格，写码时补拉全部 35 分区（§2.4） |
| 8 | 整站背景色 | 设计稿 `#F0F4F8`：已在覆盖布局 `resources/views/cms-overrides/components/layouts/app.blade.php` 的 body 上应用（`bg-[#F0F4F8] dark:bg-gray-950`） |

### 3.0 视图覆盖机制（cms-overrides，用户已搭建）

`app/Providers/AppServiceProvider.php` 中：`View::prependNamespace('sn-cms', resource_path('views/cms-overrides'))` —— `resources/views/cms-overrides/` 下的同名视图**优先于**扩展包视图解析。已覆盖/新增的文件：

| 文件 | 作用 |
|---|---|
| `components/layouts/app.blade.php` | 全站 layout（body 背景色 `#F0F4F8`） |
| `container/page.blade.php` | **页面容器覆盖**（顶工具条 + 页头 + 导航 + slot + 页脚），`config` 无需修改 |
| `livewire/tradition/components/footer.blade.php` | **页脚视图覆盖**（五列 + 主题色底部条），沿用原组件数据（`$navigations` + `$general`） |

---

## 9. 验收清单

- [ ] PC（≥lg）/ 平板（md–lg）/ 移动端（<md）三种宽度下，各区块栅格符合 §4 表格，无横向滚动
- [ ] 首屏：≥lg 左右各 50% 带 gap；<lg 上下堆叠
- [ ] 左侧轮播图：置顶文章（≤10 条），文案/玻璃标签/指示点样式贴近 §2.3-C（标签行置于标题上方）
- [ ] 右侧列表：无大标题；分类 tab ≤5 个、默认选中第一个、点击分类仅刷新右侧列表；行样式按 §2.3-D
- [ ] 统计卡：图标圆角容器 + 主题蓝 24px 数字，栅格 1/2/4
- [ ] 人才储备卡片：圆形头像 + 右侧字段竖排 + 底部描边查看详情按钮（参考图+设计稿混合方案 §2.3-G）
- [ ] 服务案例卡片：图 + 彩色分类 pill + 标题/描述/合作单位
- [ ] 科研成果卡片：图 + 标题 + 类型标签 + 元信息，lg 3 列，"查看更多"对齐修正
- [ ] 人才储备列表页单项样式与首页一致
- [ ] 顶部工具条 + 页头 + 页脚（含底部条）按 §2.3-A/B/J 重做；导航与内容区机制未变
- [ ] 全部主题蓝使用 `primary-*`；暗黑模式正常
- [ ] 其余页面（文章列表/详情、导航页等）未受影响

---

## 10. 实施记录（2026-08-22，已完成首轮实现）

| 文件 | 动作 |
|---|---|
| `resources/views/cms-overrides/components/layouts/app.blade.php` | body 背景色 `#F0F4F8`；移除旧 footer-styles include |
| `resources/views/cms-overrides/components/container/page.blade.php` | 新建页面容器（顶条 + 页头 + 导航 + slot + 页脚）；⚠️ 必须放在 `components/` 子路径下，`<x-sn-cms::container.page>` 猜测的视图名带 `components.` 前缀 |
| `resources/views/cms-overrides/livewire/tradition/components/footer.blade.php` | 按设计稿重写（品牌/平台导航/服务支持/联系我们/二维码 五列 + 主题色底部条） |
| `app/Livewire/Components/Index/Featured.php` + `resources/views/livewire/components/index/featured.blade.php` | 新建首屏组件：置顶优先轮播（≤10 条，`JSON_CONTAINS(flags,'top') DESC` 排序，无置顶时自然回落到最新文章）+ 分类 tab 文章列表（默认第一个分类，点击仅刷新列表） |
| `resources/views/components/index/section-header.blade.php` | 区块头公共组件 `<x-index.section-header>`（渐变竖条 + 标题 + 描述 + 查看更多） |
| `resources/views/livewire/index.blade.php` | 重写为：Featured + 统计 + 人才 + 服务案例 + 科研成果 |
| `.../index/overview.blade.php` | 统计卡：图标圆角容器 + primary 数字，1/2/4 栅格 |
| `.../index/personnels.blade.php` | 人才卡：圆形头像 + 字段竖排 + 描边查看详情按钮 |
| `.../index/posts.blade.php` | 服务案例卡：图 + 分类彩 pill + 标题/描述/日期（limit=4） |
| `.../index/scientific-research.blade.php` | 科研成果卡：图 + 标题 + 分类标签 + 描述/日期，lg 3 列 |
| `resources/views/livewire/components/personnels.blade.php` | 列表页单项样式与首页对齐 |
| `app/Providers/AppServiceProvider.php` | 注册 `sn-components-index-featured` |

浏览器验证（桌面 1600 / 移动 375）：顶条 41px、页头 96px 与设计稿一致；轮播比例 616:420、10 张轮播、5 个分类 tab、点击分类仅刷新列表且轮播不变；统计 4 列 / 服务案例 4 列 / 科研 3 列；移动端竖排单列无横向滚动。

## 11. 优化迭代记录（2026-08-22 第二轮）

1. **栅格断点调整**：统计/人才储备/服务案例的四列由 `lg` 改为 `xl`（1 / 2 / ≥xl 4）。
2. **人才卡片名称行**：`professional_title` 标签移到人员名称右侧同一行（`flex` + 名称 `truncate`，标签 `shrink-0`），宽度不足时名称省略；列表页同步。
3. **首屏左右等高**：
   - 等高方案：`lg` 起**整行约束宽高比** `lg:aspect-[1232/420]`（左右两半各约 616:420），子元素 `lg:h-full` 强制等高（此前由列表内容撑高导致不一致）。
   - 多查少显：`postLimit = 6`；第 5 条 `hidden @[820px]:block`、第 6 条 `hidden @[955px]:block`（容器查询挂在右侧卡片 `@container` 上，高度∝宽度，宽时多显示、窄时隐藏）。
   - 兜底：列表 `lg:flex-1 lg:min-h-0 lg:overflow-y-auto sn-scrollbar`，放不下时内部滚动（不硬裁半行）。
4. **页头响应式修复**：英文副标题 `xl` 以下隐藏、搜索框 `lg` 起显示（输入框 `w-44 xl:w-72`）、站名可截断——修复 768–1100 视口横向溢出。
5. **全站横向溢出兜底**：`resources/css/app.css` 的 `html/body` 增加 `overflow-x: clip`（Tailwind `container` 在整断点宽度 1024/1280/1536 不含滚动条 15px，属全站既有问题）。

验证（等高/无横向滚动）：375/768/900 堆叠正常；1024 并排 333/333 等高、统计 2 列；1280 420/420、4 列；1600 513/513、4 列。

遗留占位（等待真实数据）：租户 GeneralSettings 的电话/地址/邮箱/二维码/版权均为空，页脚显示占位；服务支持的"常见问题/下载中心"为 `#` 链接；服务案例卡片底部行显示日期（设计稿为"合作单位"）。

## 12. 优化迭代记录（2026-08-22 第三轮，按 tasks/首页优化计划.md）

1. **页头**：容器去内边距、logo 放大（h-16/md:h-20）、移除站名中英文（与顶条重复）；内边距移至右侧搜索/登录注册组。
2. **轮播重写**：弃用 swiper，自定义 Alpine 轮播——每帧显示 标题 + 描述 + 该文章**全部分类**玻璃标签（替换写死的四个标签）；左右箭头 + 主题色长条指示点 + 5s 自动播放（悬停暂停）；`x-data` 用单行无方法写法。
3. **右侧列表**：固定 4 条，`lg:grid lg:grid-rows-4` 行高自适应（参考 vendor IndexPosts 的 grid-rows 方案），缩略图 `lg:h-full lg:aspect-[5/4]` 随行高缩放。
4. **分类 tab**：`overflow-y-hidden` 去竖向滚动条；下划线改"贴线"（去 `-mb-px`，避免压线被滚动容器裁剪）。
5. **区块间距**：py-8 → py-6。
6. **科研成果**：图片贴上下左三边 + `sn-motion-scale` 悬停放大；后按用户反馈改为 **w-28 + `aspect-[290/176]`（与服务案例同比）+ `self-center` + `object-cover`** 限高显示中间区域；最终定稿（用户确认）：**卡片高度写死 `h-28`（112px）、图片区 `h-full aspect-[290/176]`（宽约 185px，与服务案例同比例）、贴上下左三边、右侧无圆角、cover 居中裁剪**，右侧描述收敛为单行防溢出。
7. **页脚重写**：flex 三段式——左 logo（≥lg）、中导航平铺（排除 `NavigationType::Child`，容器查询 `@md/@2xl/@4xl` 自适应列数）、右联系我们（二维码+联系方式，复用 cms footer 数据）；底部条主题色背景版权+备案。
8. **首屏宽度溢出修复（用户截图反馈）**：左右 `lg:w-1/2` + gap 超容器 24px 且左栏 `shrink-0` 阻止收缩 → 最终方案 **flex 行 + 两栏 `lg:w-[calc(50%-12px)]` 确定性均分**（曾试 `lg:flex-1` 出现 716/764 不均分、`lg:grid-cols-2` 出现行 aspect 失效导致行高塌陷至 4502px——grid 轨道按内容撑开、图片自然尺寸经 h-full/aspect 链泄漏；flex 下行 aspect 生效）。
9. **轮播 <lg 高度塌陷修复**：容器补 `aspect-[616/420] lg:aspect-auto`（子元素全 absolute）。

验证：1600 均分 740/740 等高 513、单帧显示、行宽=统计区宽 1504；1280/1100/1024 等高 420/338/333；768/375 堆叠轮播可见；全视口无横向滚动。
