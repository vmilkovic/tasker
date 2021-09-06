import { getProviders, getSession, getCsrfToken } from "next-auth/client";
import Authenticate from "../../components/layouts/Authenticate";
import Layout from "../../components/layouts/Layout";

function PageAnalyze({ session, csrfToken, providers }) {
  if (!session)
    return <Authenticate csrfToken={csrfToken} providers={providers} />;

  return <Layout title="Analyze">Page Analyze</Layout>;
}

export default PageAnalyze;

export async function getServerSideProps(context) {
  return {
    props: {
      session: await getSession(context),
      csrfToken: await getCsrfToken(context),
      providers: await getProviders(),
    },
  };
}
