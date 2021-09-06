import { getProviders, getSession, getCsrfToken } from "next-auth/client";
import Authenticate from "../../components/layouts/Authenticate";
import Layout from "../../components/layouts/Layout";

function PageWorkspace({ session, csrfToken, providers }) {
  if (!session)
    return <Authenticate csrfToken={csrfToken} providers={providers} />;

  return <Layout title="Workspaces">Page Workspace</Layout>;
}

export default PageWorkspace;

export async function getServerSideProps(context) {
  return {
    props: {
      session: await getSession(context),
      csrfToken: await getCsrfToken(context),
      providers: await getProviders(),
    },
  };
}
