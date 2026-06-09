# Resource 资源统计分析功能开发计划

## 一、项目概况分析

### 1.1 项目背景
项目 `tenancyv4` 是一个基于 Laravel + Filament 的**种质资源管理平台**，涵盖种质评价、收集、保存、编目、新品种、精准鉴定、表型鉴定、论文、专利、奖项、项目管理、人员管理等业务模块。

### 1.2 现有统计基础
- 已有一个 `App\Filament\Widgets\AppraiseStat` 小部件，实现了基础的统计卡片（种质评价数量、收集数量、保存数量、用种申请状态统计）
- 使用 Filament 内置的 `StatsOverviewWidget` 组件

### 1.3 共有 21 个 Resource（排除 1 个测试资源 + 1 个共享资源(仍为TODO状态)，实际可分析 **19 个**）

| # | Resource | Model | 表名 | 中文名 | 是否可统计 |
|---|----------|-------|------|--------|-----------|
| 1 | AccurateIdentifyResource | AccurateIdentify | accurate_identifies | 精准鉴定 | 是 |
| 2 | ActivityLogResource | ActivityLog | activity_log | 活动日志 | 是 |
| 3 | AppraiseApplyResource | AppraiseApply | appraise_applies | 种质申请 | 是 |
| 4 | AppraiseResource | Appraise | appraises | 种质评价 | 是(核心) |
| 5 | AssembleResource | Assemble | assembles | 收集 | 是 |
| 6 | AwardTypeResource | AwardType | award_types | 奖项类型 | 是 |
| 7 | AwardResource | Award | awards | 奖项 | 是 |
| 8 | CatalogResource | Catalog | catalogs | 编目 | 是 |
| 9 | CompanyResource | Company | companies | 单位 | 是 |
| 10 | NewVarietyResource | NewVariety | new_varieties | 新品种 | 是 |
| 11 | PatentTypeResource | PatentType | patent_types | 专利类型 | 是 |
| 12 | PatentResource | Patent | patents | 专利 | 是 |
| 13 | PersonnelResource | Personnel | personnels | 人员管理 | 是 |
| 14 | PhenotypeIdentifyResource | PhenotypeIdentify | phenotype_identifies | 表型鉴定 | 是 |
| 15 | PreserveResource | Preserve | preserves | 保存 | 是 |
| 16 | ProjectManageResource | ProjectManage | project_manages | 项目管理 | 是 |
| 17 | ShareResource | Share | (appraises表-todo) | 共享 | 否(TODO) |
| 18 | TestResource | Company | companies | 测试 | 否(测试) |
| 19 | ThesisResource | Thesis | theses | 论文 | 是 |
| 20 | ThesisTypeResource | ThesisType | thesis_types | 论文类型 | 是 |
| 21 | UserResource | User | users | 管理员 | 是 |

---

## 二、各 Resource 表字段详细梳理

### 2.1 Appraise（种质评价）- 核心表
```
核心字段: id, team_id, category_id, resource_no(全国统一编号), germplasm_no(种质圃编号),
         original_no(引种号), gather_no(采集号), name(种质名称), en_name(种质外文名),
         subject_name(科名), genus_name(属名), species_name(学名)
产地信息: country_code/name, province_name/CID, city_name/CID, address, altitude, longitude, latitude
来源信息: source_country_code/name, source_province_name/CID, source_city_name/CID, source_address
保存/育种: save_company, save_company_no, pedigree, breeding_company, cultivationd_at(育成年份), breeding_method
属性分类: germplasm_type(种质类型), germplasm_use(用途), fruit_use(果实用途), plant_use(植株用途),
         assemble_resource, assemble_material_type, observe_place
NHGRC:   nhgrc_pending_id, nhgrc_external_ref
状态/时间: status(Normal/Hidden), options(json), created_at, updated_at, deleted_at
```
关联: category分类, saveCompany保存单位, breedingCompany选育单位, preserves保存记录

### 2.2 Assemble（收集）
```
字段: id, team_id, appraise_id, name(收集人), company(收集单位), assemble_no(收集编号),
      subject_no(所属课题编号), sub_subject_no(所属子课题编号),
      longitude, latitude, country_code/name, province_name/CID, city_name/CID, address,
      status, created_at
```

### 2.3 Preserve（保存）
```
字段: id, team_id, appraise_id, preserve_no(保存编号), preserve_position(保存位置),
      status, created_at
```

### 2.4 Catalog（编目）
```
字段: id, team_id, appraise_id, name(作物名称), code_type(编码类别), assemble_no(收集编号),
      original_no(原始编号), assemble_at(收集日期), resource_method(资源来源方式),
      catalog_at(编目时间),
      产地: country_code/name, province_name/CID, city_name/CID, address, altitude, longitude, latitude
      来源: source_country_code/name, source_province_name/CID, source_city_name/CID, source_address
      收集: assemble_address, assemble_company, assembler, assembler_phone, provider, provider_phone
      保存: temp_save_company, original_save_company, original_save_company_no
      inspect_assemble_project, status, created_at
```

### 2.5 NewVariety（新品种）
```
字段: id, team_id, appraise_id, variety_no(品种权号), name(品种权人),
      variety_at(年份), cultivate_name(培育人), status, created_at
```

### 2.6 AccurateIdentify（精准鉴定）
```
字段: id, team_id, appraise_id, name, content, status(Normal/Hidden), created_at
```

### 2.7 PhenotypeIdentify（表型鉴定）
```
字段: id, team_id, appraise_id, name, content, status(Normal/Hidden), created_at
```

### 2.8 AppraiseApply（种质申请/用种申请）
```
字段: id, team_id, appraise_id, user_id, name(申请人), phone(联系方式),
      company_name(用种单位), status(Applying/Agree/Refuse), created_at
```

### 2.9 Thesis（论文）
```
字段: id, team_id, thesis_type_id, title(标题), author_name(作者), company_name(所属单位),
      description(摘要), journal(发布期刊), issue_number(卷期号),
      published_at(出版日期), remark, status, created_at
```

### 2.10 ThesisType（论文类型）
```
字段: id, team_id, name(类型名称), status, created_at
```

### 2.11 Patent（专利）
```
字段: id, team_id, patent_type_id, name(名称), patent_apply_no(专利申请号),
      patent_no(专利号), applied_at(申请日期), authd_at(授权日期),
      status(专利状态), author_name(发明人), description(摘要), remark, created_at
```

### 2.12 PatentType（专利类型）
```
字段: id, team_id, name(类型名称), status, created_at
```

### 2.13 Award（奖项）
```
字段: id, team_id, award_type_id, name(名称), award_agency(授奖机构),
      award_at(获奖日期), level(级别), award_name(获奖人/团队), remark, status, created_at
```

### 2.14 AwardType（奖项类型）
```
字段: id, team_id, name(类型名称), status, created_at
```

### 2.15 ProjectManage（项目管理）
```
字段: id, team_id, project_no(项目编号), name(项目名称), type(项目类型),
      subject(所属学科), initiation_company(立项单位), level(项目级别),
      manager_name(负责人), attend_name(参与人), start_at(开始时间),
      end_at(结束时间), budget(总预算), status, created_at
```

### 2.16 Personnel（人员管理）
```
字段: id, team_id, name(姓名), qualification(学历), professional_title(职称),
      research_focus(研究方向), research_result(研究成果), intro(个人简介),
      views(浏览量), status, is_display(是否展示), created_at
```

### 2.17 Company（单位）
```
字段: id, team_id, name(名称), code(编号), contact(联系人), contact_phone(联系方式),
      email(邮箱), province_name/CID, city_name/CID, district_name/CID, address(地址),
      status, created_at
```

### 2.18 User（管理员）
```
字段: id, team_id, name, email, password, avatar_url, is_admin, created_at 等
```

### 2.19 ActivityLog（活动日志）
```
字段: id, team_id, log_name, description, event, subject_type, subject_id,
      causer_type, causer_id, properties(json), batch_uuid, created_at
```

---

## 三、统计分析功能规划

### 第一类：核心仪表盘 Widget（已有基础，需扩展）

#### 统计功能 1: 种质资源总览仪表盘
| 属性 | 内容 |
|------|------|
| **名称** | 种质资源总览统计卡片 |
| **涉及 Resource** | Appraise, Assemble, Preserve, Catalog, NewVariety, AppraiseApply |
| **数据字段** | 各表 count(*) |
| **图表类型** | `StatsOverviewWidget` 统计卡片（已存在，需扩展） |
| **展示效果** | 一行多个统计数字卡片：评价总数、收集总数、保存总数、编目总数、新品种总数、待处理申请数 |
| **复杂度** | 低 - 仅扩展现有 `AppraiseStat` Widget |

---

### 第二类：图表 Widget（使用 Filament Chart Widget）

#### 统计功能 2: 种质评价月度新增趋势
| 属性 | 内容 |
|------|------|
| **名称** | 种质评价月度趋势图 |
| **涉及 Resource** | Appraise |
| **数据字段** | created_at（按月分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 近12个月每月新增种质评价数量趋势 |
| **复杂度** | 低 |
| **选择理由** | 折线图能清晰展示时间序列上的增长趋势和波动 |

#### 统计功能 3: 种质按分类分布
| 属性 | 内容 |
|------|------|
| **名称** | 种质分类分布图 |
| **涉及 Resource** | Appraise |
| **数据字段** | category_id（按分类分组 count） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各分类下种质评价数量对比 |
| **复杂度** | 低 |
| **选择理由** | 柱状图适合横向对比不同分类的数据量大小 |

#### 统计功能 4: 种质原产地分布（Top N）
| 属性 | 内容 |
|------|------|
| **名称** | 种质原产地来源分布 |
| **涉及 Resource** | Appraise |
| **数据字段** | province_name / country_name（按省份/国家分组 count） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各省份种质来源数量排名 Top 10 |
| **复杂度** | 低 |
| **选择理由** | 地理分布适合用柱状图展示各区域数量差异 |

#### 统计功能 5: 种质类型分布
| 属性 | 内容 |
|------|------|
| **名称** | 种质类型/用途分析 |
| **涉及 Resource** | Appraise |
| **数据字段** | germplasm_type, germplasm_use |
| **图表类型** | **柱状图** `BarChartWidget`（可做两个 chart） |
| **展示效果** | 种质类型分布、种质用途分布 |
| **复杂度** | 低 |

#### 统计功能 6: 种质收集年度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 收集记录年度趋势 |
| **涉及 Resource** | Assemble |
| **数据字段** | created_at（按年/月分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 各年/月收集记录数量变化趋势 |
| **复杂度** | 低 |

#### 统计功能 7: 收集地区分布
| 属性 | 内容 |
|------|------|
| **名称** | 收集地分布图 |
| **涉及 Resource** | Assemble |
| **数据字段** | province_name（按省分组 count） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各省收集记录数量排名 |
| **复杂度** | 低 |

#### 统计功能 8: 论文数量年度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 论文发表年度趋势 |
| **涉及 Resource** | Thesis |
| **数据字段** | published_at（按年份分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 每年发表论文数量趋势，直观反映学术产出变化 |
| **复杂度** | 低 |
| **选择理由** | 论文发表有明显的时间属性，折线图展示逐年趋势最佳 |

#### 统计功能 9: 论文类型分布
| 属性 | 内容 |
|------|------|
| **名称** | 论文类型分布 |
| **涉及 Resource** | Thesis, ThesisType |
| **数据字段** | thesis_type_id（关联 thesis_types.name） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各类型论文数量对比（期刊论文/会议论文/学位论文等） |
| **复杂度** | 低 |

#### 统计功能 10: 专利年度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 专利申请/授权年度趋势 |
| **涉及 Resource** | Patent |
| **数据字段** | applied_at, authd_at（按年份分组 count） |
| **图表类型** | **折线图** `LineChartWidget`（可双线对比申请vs授权） |
| **展示效果** | 逐年专利申请量 + 授权量对比折线图 |
| **复杂度** | 中 |
| **选择理由** | 双折线图可直观对比申请和授权的差距，反映专利转化率 |

#### 统计功能 11: 专利类型分布
| 属性 | 内容 |
|------|------|
| **名称** | 专利类型分布 |
| **涉及 Resource** | Patent, PatentType |
| **数据字段** | patent_type_id（关联 patent_types.name） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 发明专利/实用新型/外观设计等类型分布 |
| **复杂度** | 低 |

#### 统计功能 12: 奖项年度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 获奖年度趋势 |
| **涉及 Resource** | Award |
| **数据字段** | award_at（按年份分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 每年获奖数量趋势 |
| **复杂度** | 低 |

#### 统计功能 13: 奖项类型分布
| 属性 | 内容 |
|------|------|
| **名称** | 奖项类型分布 |
| **涉及 Resource** | Award, AwardType |
| **数据字段** | award_type_id（关联 award_types.name） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各类奖项数量对比 |
| **复杂度** | 低 |

#### 统计功能 14: 奖项级别分布
| 属性 | 内容 |
|------|------|
| **名称** | 奖项级别统计 |
| **涉及 Resource** | Award |
| **数据字段** | level（国家级/省部级/市级等） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各等级奖项数量 |
| **复杂度** | 低 |

#### 统计功能 15: 项目年度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 项目立项年度趋势 |
| **涉及 Resource** | ProjectManage |
| **数据字段** | start_at / created_at（按年份分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 每年新立项项目数量趋势 |
| **复杂度** | 低 |

#### 统计功能 16: 项目类型/级别分布
| 属性 | 内容 |
|------|------|
| **名称** | 项目类型与级别分布 |
| **涉及 Resource** | ProjectManage |
| **数据字段** | type, level |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 按项目类型分布、按项目级别分布 |
| **复杂度** | 低 |

#### 统计功能 17: 项目经费汇总
| 属性 | 内容 |
|------|------|
| **名称** | 年度项目经费统计 |
| **涉及 Resource** | ProjectManage |
| **数据字段** | budget（按年份 sum 汇总） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各年度项目总经费柱状图 |
| **复杂度** | 低 |

#### 统计功能 18: 用种申请状态统计
| 属性 | 内容 |
|------|------|
| **名称** | 用种申请状态分布 |
| **涉及 Resource** | AppraiseApply |
| **数据字段** | status（Applying/Agree/Refuse 分组 count） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 待处理/已同意/已拒绝 数量对比 |
| **复杂度** | 低（现有 Widget 已有基础数据，转为图表即可） |

#### 统计功能 19: 用种申请月度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 用种申请月度趋势 |
| **涉及 Resource** | AppraiseApply |
| **数据字段** | created_at（按月分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 近12月用种申请数量变化 |
| **复杂度** | 低 |

#### 统计功能 20: 人员学历/职称分布
| 属性 | 内容 |
|------|------|
| **名称** | 人员学历与职称分布 |
| **涉及 Resource** | Personnel |
| **数据字段** | qualification, professional_title |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 博士/硕士/本科等学历分布 + 教授/副教授/研究员等职称分布 |
| **复杂度** | 低 |

#### 统计功能 21: 人员研究方向分布
| 属性 | 内容 |
|------|------|
| **名称** | 研究方向分布 |
| **涉及 Resource** | Personnel |
| **数据字段** | research_focus（分组 count） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各研究方向人员数量 |
| **复杂度** | 低 |

#### 统计功能 22: 新品种年度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 新品种审定年度趋势 |
| **涉及 Resource** | NewVariety |
| **数据字段** | variety_at（按年份分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 每年新品种审定数量趋势 |
| **复杂度** | 低 |

#### 统计功能 23: 编目年度趋势
| 属性 | 内容 |
|------|------|
| **名称** | 编目年度趋势 |
| **涉及 Resource** | Catalog |
| **数据字段** | catalog_at（按年份分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 每年编目数量趋势 |
| **复杂度** | 低 |

#### 统计功能 24: 系统操作活跃度
| 属性 | 内容 |
|------|------|
| **名称** | 系统操作活跃度趋势 |
| **涉及 Resource** | ActivityLog |
| **数据字段** | created_at（按日/周分组 count） |
| **图表类型** | **折线图** `LineChartWidget` |
| **展示效果** | 每日/每周系统操作次数趋势 |
| **复杂度** | 低 |

#### 统计功能 25: 操作事件类型分布
| 属性 | 内容 |
|------|------|
| **名称** | 操作类型分布 |
| **涉及 Resource** | ActivityLog |
| **数据字段** | event（created/updated/deleted 等分组 count） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各种操作类型（创建/更新/删除等）发生次数 |
| **复杂度** | 低 |

#### 统计功能 26: 单位地区分布
| 属性 | 内容 |
|------|------|
| **名称** | 合作单位地区分布 |
| **涉及 Resource** | Company |
| **数据字段** | province_name（按省分组 count） |
| **图表类型** | **柱状图** `BarChartWidget` |
| **展示效果** | 各省合作单位数量分布 |
| **复杂度** | 低 |

---

## 四、汇总分类

### 按复杂度分类

| 复杂度 | 数量 | 功能编号 |
|--------|------|----------|
| 低（纯 count 统计） | 23 | #1-#9, #11-#26 |
| 中（双线对比） | 1 | #10 |
| 高 | 0 | - |

### 按图表类型分类

| 图表类型 | 数量 | 功能编号 |
|----------|------|----------|
| 统计卡片 `StatsOverviewWidget` | 1 | #1 |
| **柱状图** `BarChartWidget` | 14 | #3, #4, #5, #7, #9, #11, #13, #14, #16, #17, #18, #20, #21, #25, #26 |
| **折线图** `LineChartWidget` | 10 | #2, #6, #8, #10, #12, #15, #19, #22, #23, #24 |

### 按 Resource 覆盖

| Resource | 涉及统计功能数 |
|----------|--------------|
| Appraise（种质评价） | 4 (#1, #2, #3, #4, #5) |
| Assemble（收集） | 2 (#1, #6, #7) |
| Preserve（保存） | 1 (#1) |
| Catalog（编目） | 2 (#1, #23) |
| NewVariety（新品种） | 2 (#1, #22) |
| AccurateIdentify（精准鉴定） | 1 (#1 扩展) |
| PhenotypeIdentify（表型鉴定） | 1 (#1 扩展) |
| AppraiseApply（种质申请） | 3 (#1, #18, #19) |
| Thesis（论文） | 2 (#8, #9) |
| ThesisType（论文类型） | 1 (#9) |
| Patent（专利） | 2 (#10, #11) |
| PatentType（专利类型） | 1 (#11) |
| Award（奖项） | 3 (#12, #13, #14) |
| AwardType（奖项类型） | 1 (#13) |
| ProjectManage（项目管理） | 3 (#15, #16, #17) |
| Personnel（人员） | 2 (#20, #21) |
| Company（单位） | 1 (#26) |
| ActivityLog（活动日志） | 2 (#24, #25) |
| User（管理员） | - (包含于 ActivityLog) |

---

## 五、推荐实施优先级

### 第一批（高价值、低复杂度）- 建议优先实现
1. **种质评价月度趋势** (#2) - 核心业务指标
2. **种质分类分布** (#3) - 核心业务分析
3. **种质原产地分布** (#4) - 特色数据展示
4. **论文发表年度趋势** (#8) - 学术产出可视化
5. **专利申请/授权年度趋势** (#10) - 双线对比有看点
6. **项目立项年度趋势** (#15) - 项目管理核心指标
7. **用种申请月度趋势** (#19) - 业务动态监控

### 第二批（辅助分析）
8. **种质类型分布** (#5)
9. **收集年度趋势** (#6) + **收集地区分布** (#7)
10. **论文类型分布** (#9)
11. **专利类型分布** (#11)
12. **奖项年度趋势** (#12) + **级别分布** (#14)
13. **项目类型/级别分布** (#16) + **经费汇总** (#17)
14. **新品种年度趋势** (#22)
15. **编目年度趋势** (#23)

### 第三批（辅助治理）
16. **用种申请状态分布** (#18)
17. **人员学历/职称分布** (#20) + **研究方向** (#21)
18. **系统操作活跃度** (#24) + **操作类型分布** (#25)
19. **单位地区分布** (#26)

---

## 六、技术实现方案

### 6.1 目录结构
```
app/Filament/Widgets/
├── AppraiseStat.php              # 现有 - 扩展
├── Charts/
│   ├── AppraiseMonthlyTrend.php          # 功能 #2
│   ├── AppraiseCategoryDistribution.php  # 功能 #3
│   ├── AppraiseOriginDistribution.php    # 功能 #4
│   ├── AppraiseTypeDistribution.php      # 功能 #5
│   ├── AssembleYearTrend.php             # 功能 #6
│   ├── AssembleRegionDistribution.php    # 功能 #7
│   ├── ThesisYearTrend.php               # 功能 #8
│   ├── ThesisTypeDistribution.php        # 功能 #9
│   ├── PatentYearTrend.php               # 功能 #10
│   ├── PatentTypeDistribution.php        # 功能 #11
│   ├── AwardYearTrend.php                # 功能 #12
│   ├── AwardTypeDistribution.php         # 功能 #13
│   ├── AwardLevelDistribution.php        # 功能 #14
│   ├── ProjectYearTrend.php              # 功能 #15
│   ├── ProjectTypeDistribution.php       # 功能 #16
│   ├── ProjectBudgetSummary.php          # 功能 #17
│   ├── AppraiseApplyStatus.php           # 功能 #18
│   ├── AppraiseApplyMonthlyTrend.php     # 功能 #19
│   ├── PersonnelEducationStats.php       # 功能 #20
│   ├── PersonnelResearchStats.php        # 功能 #21
│   ├── NewVarietyYearTrend.php           # 功能 #22
│   ├── CatalogYearTrend.php              # 功能 #23
│   ├── ActivityTrend.php                 # 功能 #24
│   ├── ActivityEventDistribution.php     # 功能 #25
│   └── CompanyRegionDistribution.php     # 功能 #26
```

### 6.2 技术要点
- 使用 Filament 内置的 `Filament\Widgets\ChartWidget` 作为图表 Widget 基类
- 图表组件使用 Filament 自带的 `BarChartWidget`（柱状图）和 `LineChartWidget`（折线图）
- 数据查询使用 Laravel Eloquent ORM 的 `groupBy` + 聚合函数
- 所有查询需限定 `team_id` 以支持多租户数据隔离
- 考虑使用 `->whereNull('deleted_at')` 排除软删除数据
- 月度/年度聚合使用 `DB::raw('DATE_FORMAT(created_at, "%Y-%m")')` 或 Carbon 处理

### 6.3 示例代码结构（以柱状图为例）
```php
namespace App\Filament\Widgets\Charts;

use App\Models\Appraise;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AppraiseCategoryDistribution extends ChartWidget
{
    protected static ?string $heading = '种质分类分布';
    
    protected function getType(): string
    {
        return 'bar'; // 或 'line'
    }
    
    protected function getData(): array
    {
        $data = Appraise::select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();
        
        return [
            'datasets' => [
                [
                    'label' => '种质数量',
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->pluck('category.name')->toArray(),
        ];
    }
}
```

---

## 七、风险与注意事项

1. **数据量评估**: Appraise 表可能是最大的表，需关注查询性能，建议对 `created_at`, `category_id`, `status` 添加索引（已存在 team_id 和 category_id 索引）
2. **多租户**: 所有统计必须使用 `team_id` 隔离，可通过 `Filament::getTenant()` 获取当前团队 ID
3. **Dashboard 布局**: 过多 Widget 会导致仪表盘加载缓慢，建议按模块分 Tab 或使用独立的统计页面
4. **缓存策略**: 对于不常变化的数据（如分类分布），可考虑添加缓存机制减少数据库压力
5. **图表中文支持**: Filament 图表底层使用 Chart.js，需确保中文字体在图表中正常渲染

---

## 八、下一步行动

请用户审阅上述统计功能列表，确定需要实现哪些功能模块，以及优先级排序。确认后将逐项实施开发。