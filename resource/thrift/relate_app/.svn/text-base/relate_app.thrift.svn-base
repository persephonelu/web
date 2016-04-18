namespace php rela_app

struct RequestBrief {
  1: string category,
  2: string brief,
  3: optional string appname
}

struct RelaApp {
  1: double score,
  2: string name
}

struct ResponseRelaapp {
  1: i32 status,
  2: list<RelaApp> relaapps
}

service RelaAppService {
  ResponseRelaapp getRelaApps(1: RequestBrief req)
}
